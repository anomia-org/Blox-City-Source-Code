<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Guild;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemData;
use App\Models\ItemReseller;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class MarketController extends Controller
{
    public function index(Request $request)
    {

        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        $items = Item::latest();

        $advanced_sort = request('advanced');
        $sort = request('sort');
        $query = request('query');

        if($sort)
        {
            switch ($sort) {
                case 'shirts':
                    $type = 4;
                    break;
                case 'pants':
                    $type = 5;
                    break;
                case 'hats':
                    $type = 1;
                    break;
                case 'faces':
                    $type = 2;
                    break;
                case 'accessories':
                    $type = 3;
                    break;
                //case 'tshirts':
                //    $type = 6;
                //    break;
                default:
                    return abort(404);
                    break;
            }

            $items->where('type', '=', $type)->where('pending', '=', '0');
        }

        if(!$sort && !$query)
        {
            $items->where('creator_id', '=', '1');
        }

        if($query)
        {
            $items->where('name', 'LIKE', "%{$query}%")->orWhere('desc', 'LIKE', "%{$query}%");
        }

        if(!$advanced_sort)
        {
            $items = $items->orderBy('updated_real', 'DESC');
        } elseif ($advanced_sort == 2)
        {
            $items = $items->orderBy('updated_real', 'DESC');
        } elseif ($advanced_sort == 3)
        {
            $items = $items->orderBy('updated_real', 'ASC');
        } elseif ($advanced_sort == 4)
        {
            $items = $items->orderBy('cash', 'ASC')->orderBy('coins', 'ASC');
        } elseif ($advanced_sort == 5)
        {
            $items = $items->orderBy('cash', 'DESC')->orderBy('coins', 'DESC');
        }

        $items = $items->paginate('12');

        if($request->ajax())
        {
            $view = view('components.load_market', compact('items'))->render();
            return response()->json(['html' => $view]);
        }

        return view('market.index', compact(['items']));
    }

    public function edit(Request $request, Item $item)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->id != $item->owner->id)
            return abort(403);
        $min = 'min:0';
        if(auth()->user()->power > 0) { $min = 'min:-1'; }

        request()->validate([
            'title' => ['required', 'min:3', 'max:64', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&]+$/i'],
            'description' => ['max:2048', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]+/i', 'nullable'],
            'cash' => ['numeric', $min],
            'coins' => ['numeric', $min],
        ],
    [
        'regex' => 'The :attribute format is invalid.',
    ]);

        $cash = $request['cash'];
        $coins = $request['coins'];

        if($request->has('offsale'))
        {
            $cash = 0;
            $coins = 0;
        }

        if($request->has('free'))
        {
            $cash = -1;
            $coins = -1;
        }

        $update = $item->update([
            'name' => $request['title'],
            'desc' => $request['description'],
            'cash' => $cash,
            'coins' => $coins,
            'updated_real' => Carbon::now(),
        ]);

        return back()->with('success', 'Item successfully updated!');
    }

    public function edit_item(Request $request, Item $item)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        $item = Item::find($item->id);

        if(!$item->exists())
        {
            abort(404);
        }

        if(auth()->user()->id != $item->owner->id)
            return abort(403);

        return view('market.edit', compact(['item']));
    }

    public function show_item(Request $request, Item $item)
    {
        if(!$item->exists) {
            abort(404);
        }

        //return $item->free();

        $comments = Comment::where('target_id', '=', $item->id)->where('type', '=', '1')->orderBy('created_at', 'DESC')->paginate('5');

        if($request->ajax() && $comments->count() > 0)
        {
            $view = view('components.load_item_comments', compact('comments'))->render();
            return response()->json(['html' => $view]);
        }

        $markets = $item->market()->paginate(5, '*', 'resellers');

        $suggestions = Item::where([['id', '!=', $item->id], ['type', '=', $item->type], ['pending', '=', '0'], ['special', '=', '0']])
            ->where(function ($query) {
                $query->where('cash', '!=', 0)
                    ->orWhere('coins', '!=', 0);
            })
            ->inRandomOrder()->take(4)
            ->get();

        return view('market.item', compact(['item', 'comments', 'markets', 'suggestions']));
    }

    public function add_comment(Request $request, Item $item)
    {
        // Acquire lock
        $lockKey = 'lock:comment:'.$item->id.':' . auth()->id();
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5); // Lock expires in 5 seconds

        if (!$lockAcquired) {
            return back()->with('error', 'Please wait 5 seconds before making another request.');
        }

        try {
            DB::beginTransaction();
            if(Setting::where('market_enabled', '0')->get()->first())
            {
                DB::rollBack();
                return abort('403');
            }

            if(!$item->exists)
            {
                DB::rollBack();
                return abort('404');
            }

            if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(3)))
            {
                DB::rollBack();
                return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another request.');
            }

            $this->validate($request, [
                'body' => ['required', 'min:3', 'max:280', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i'],
            ]);

            Comment::create([
                'user_id' => auth()->id(),
                'text' => request('body'),
                'target_id' => $item->id,
            ]);

            $flood = auth()->user();
            $flood->flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', 'Successfully posted comment!');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return back()->with('error', 'There was an error trying to post this comment.');
        }
    }

    public function create_item(Request $request)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        return view('market.new');
    }

    public function create_shirt(Request $request)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        return view('market.shirt');
    }

    public function create_pants(Request $request)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return view('maintenance.disabled');
        }

        return view('market.pants');
    }

    public function upload_shirt(Request $request)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
             return back()->with('error', 'You\'re doing that too fast!');
        }

        $request->validate([
            'title' => 'required|min:3|max:64|strictly_profane|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i',
            'description' => 'max:2048|strictly_profane|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i|nullable',
            'image' => 'required|image|mimes:png|max:2048',
            'guild_id' => 'integer|nullable',
        ],
        [
            'regex' => 'The :attribute format is invalid.',
            'mimes:png' => 'The image must be a file of type: png.',
            'strictly_profane' => 'The :attribute contains inappropriate language.',
        ]);

        $creator_type = 1;
        $creator_id = auth()->user()->id;

        if(request('guild_id'))
        {
            $guild = Guild::where('id', request('guild_id'))->first();
            $creator_type = 2;
            $creator_id = $guild->id;
            if(auth()->user()->rankInGuild($guild->id)->can_create_items == 0)
            {
                return back()->with('error', 'You don\'t have permission to create items for this community!');
            }
        }

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->extension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/cdn',
        ]);

        $disk->putFileAs('', $request->image, $imageName);

        $upload = Item::create([
            'name' => request('title'),
            'desc' => request('description'),
            'creator_id' => $creator_id,
            'creator_type' => $creator_type,
            'updated_real' => Carbon::now(),
            'type' => '4',
            'source' => $realName,
            'cash' => '0',
            'coins' => '0',
            'sales' => '0',
            'hash' => '1',
        ]);

        $grantItem = DB::table('inventories')->insert([
            'user_id' => Auth::user()->id,
            'item_id' => $upload->id,
            'type' => $upload->type,
            'collection_number' => $this->generateSerial(),
        ]);

        app('App\Http\Controllers\API\AvatarsController')->market($upload);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return redirect(route('market.item', $upload->id));
    }

    public function upload_pants(Request $request)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'image' => ['required', 'image', 'mimes:png', 'max:2048'],
            'title' => ['required', 'min:3', 'max:64', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i'],
            'description' => ['max:2048', 'strictly_profane', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i', 'nullable'],
            'guild_id' => ['integer', 'nullable'],
        ]);

        $creator_type = 1;
        $creator_id = auth()->user()->id;

        if(request('guild_id'))
        {
            $guild = Guild::where('id', request('guild_id'))->first();
            $creator_type = 2;
            $creator_id = $guild->id;
            if(auth()->user()->rankInGuild($guild->id)->can_create_items == 0)
            {
                return back()->with('error', 'You don\'t have permission to create items for this community!');
            }
        }

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->getClientOriginalExtension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/cdn',
        ]);

        $disk->putFileAs('', $request->image, $imageName);

        $upload = Item::create([
            'name' => request('title'),
            'desc' => request('description'),
            'creator_id' => $creator_id,
            'creator_type' => $creator_type,
            'updated_real' => Carbon::now(),
            'type' => '5',
            'source' => $realName,
            'cash' => '0',
            'coins' => '0',
            'sales' => '0',
            'hash' => '1',
        ]);

        $grantItem = Inventory::create([
            'user_id' => auth()->user()->id,
            'item_id' => $upload->id,
            'type' => $upload->type,
            'collection_number' => $this->generateSerial(),
        ]);

        app('App\Http\Controllers\API\AvatarsController')->market($upload);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return redirect(route('market.item', $upload->id));
    }

    public function comment(Request $request, Item $item)
    {
        // Check flood gate before acquiring lock
        $user = auth()->user();
        if (!$user->flood_gate || $user->flood_gate > (Carbon::now()->subSeconds(3))) {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $lockKey = 'lock:comment:' . $item->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();

            if (Setting::where('market_enabled', '0')->exists()) {
                DB::rollBack();
                Redis::del($lockKey);
                return abort('403');
            }

            $request->validate([
                'body' => ['required', 'string', 'min:3', 'max:120', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i', 'strictly_profane'],
            ]);

            Comment::create([
                'user_id' => $user->id,
                'text' => $request->body,
                'target_id' => $item->id,
            ]);

            // Update flood gate timestamp
            $user->flood_gate = Carbon::now();
            $user->save();

            DB::commit();
            Redis::del($lockKey);

            return back()->with('success', 'Comment posted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return back()->with('error', 'There was an error trying to post this comment.');
        }
    }

    public function scrub_comment(Comment $comment)
    {
        if(auth()->user()->power > 0)
        {
            $comment->scrub();
            return back();
        } else {
            return back()->with('error', 'You do not have permission to perform this action.');
        }
    }

    public function buy_item(Item $item, $type)
    {
        $lockKey = 'lock:item:' . $item->id;
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);

        if (!$lockAcquired) {
            return back()->with('error', 'You are trying too fast. Please wait and try again.');
        }

        try {
            DB::beginTransaction();

            if (Setting::where('market_enabled', '0')->exists()) {
                Redis::del($lockKey);
                return abort('403');
            }

            $user = auth()->user();

            // action flood gate
            if (!$user->action_flood_gate || $user->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE')))) {
                Redis::del($lockKey);
                return back()->with('error', 'You\'re doing that too fast!');
            }

            // Lock the item row
            $item = Item::where('id', $item->id)->lockForUpdate()->first();

            // Check ownership
            if ($user->owns($item)) {
                Redis::del($lockKey);
                return back()->with('error', 'You already own this item!');
            }

            // Check pending/deleted
            if ($item->pending != 0) {
                Redis::del($lockKey);
                return back()->with('error', 'Item is not approved!');
            }

            // Check stock
            if ($item->stock() == 0) {
                Redis::del($lockKey);
                return back()->with('error', 'This item is out of stock.');
            }

            $release = Carbon::now()->addDay();

            if ($type != 3) {
                $cashTax = $item->cash * $item->owner->salesTax();
                $coinsTax = $item->coins * $item->owner->salesTax();
                $cash = $item->cash - $cashTax;
                $coins = $item->coins - $coinsTax;
            }

            // Cash purchase
            if ($type == 1) {
                if ($user->cash >= $item->cash && $item->cash != 0) {
                    $user->revoke_currency($item->cash, $type);

                    $logPurchase = Transaction::create([
                        'user_id' => $user->id,
                        'source_id' => $item->id,
                        'source_user' => $item->owner->id,
                        'source_type' => '1',
                        'type' => '1',
                        'cash' => $item->cash,
                    ]);

                    $logSale = Transaction::create([
                        'user_id' => $item->owner->id,
                        'source_id' => $item->id,
                        'source_user' => $user->id,
                        'source_type' => '1',
                        'type' => '2',
                        'cash' => $cash,
                        'release_at' => $release,
                    ]);

                    $grantItem = Inventory::create([
                        'user_id' => $user->id,
                        'item_id' => $item->id,
                        'type' => $item->type,
                        'collection_number' => $this->generateSerial(),
                        'special' => $item->special,
                    ]);

                    $user->action_flood_gate = Carbon::now();
                    $user->save();

                    DB::commit();
                    Redis::del($lockKey);

                    return back()->with('success', 'Successfully purchased ' . $item->name . '!');
                }
            }
            // Coins purchase
            elseif ($type == 2) {
                if ($user->coins >= $item->coins && $item->coins != 0) {
                    $user->revoke_currency($item->coins, $type);

                    $logPurchase = Transaction::create([
                        'user_id' => $user->id,
                        'source_id' => $item->id,
                        'source_user' => $item->owner->id,
                        'source_type' => '1',
                        'type' => '1',
                        'coins' => $item->coins,
                    ]);

                    $logSale = Transaction::create([
                        'user_id' => $item->owner->id,
                        'source_id' => $item->id,
                        'source_user' => $user->id,
                        'source_type' => '1',
                        'type' => '2',
                        'coins' => $coins,
                        'release_at' => $release,
                    ]);

                    $grantItem = Inventory::create([
                        'user_id' => $user->id,
                        'item_id' => $item->id,
                        'type' => $item->type,
                        'collection_number' => $this->generateSerial(),
                        'special' => $item->special,
                    ]);

                    $user->action_flood_gate = Carbon::now();
                    $user->save();

                    DB::commit();
                    Redis::del($lockKey);

                    return back()->with('success', 'Successfully purchased ' . $item->name . '!');
                }
            }
            // Free purchase
            elseif ($type == 3) {
                if($item->coins == -1 && $item->cash == -1) {
                    $logPurchase = Transaction::create([
                        'user_id' => $user->id,
                        'source_id' => $item->id,
                        'source_user' => $item->owner->id,
                        'source_type' => '1',
                        'type' => '1',
                    ]);

                    $logSale = Transaction::create([
                        'user_id' => $item->owner->id,
                        'source_id' => $item->id,
                        'source_user' => $user->id,
                        'source_type' => '1',
                        'type' => '2',
                    ]);

                    $grantItem = Inventory::create([
                        'user_id' => $user->id,
                        'item_id' => $item->id,
                        'type' => $item->type,
                        'collection_number' => $this->generateSerial(),
                        'special' => $item->special,
                    ]);

                    $user->action_flood_gate = Carbon::now();
                    $user->save();

                    DB::commit();
                    Redis::del($lockKey);

                    return back()->with('success', 'Successfully purchased ' . $item->name . '!');
                } else {
                    return back()->with('error', 'Error attempting to buy item!');
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Redis::del($lockKey);
            return back()->with('error', 'There was an error trying to buy this item.');
        } finally {
            Redis::del($lockKey);
        }
    }

    public function delete(Request $request, Item $item)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $user = auth()->user();

        // check ownership
        if($user->owns($item) && $item->special == 0)
        {
            $get = Inventory::where('item_id', '=', $item->id)->where('user_id', '=', auth()->user()->id)->first();
            $get->delete();

            $user->action_flood_gate = Carbon::now();
            $user->save();

            if(auth()->user()->isWearing($item))
            {
                $avatar = auth()->user()->avatar;
                if($item->type == 1) {
                    if($avatar->hat1_id == $item->id) {
                        $avatar->hat1_id == 0;
                    } elseif($avatar->hat2_id == $item->id) {
                        $avatar->hat2_id == 0;
                    } elseif($avatar->hat3_id == $item->id) {
                        $avatar->hat3_id == 0;
                    }
                } elseif ($item->type == 2) {
                    $avatar->face_id = 0;
                } elseif ($item->type == 3) {
                    $avatar->tool_id = 0;
                } elseif ($item->type == 4) {
                    $avatar->shirt_id = 0;
                } elseif ($item->type == 5) {
                    $avatar->pants_id = 0;
                } elseif ($item->type == 6) {
                    $avatar->tshirt_id = 0;
                }
                $avatar->save();

                app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());
            }

            return back()->with('success', 'Deleted item from inventory!');
        } else {
            return back()->with('error', 'You don\'t own this item!');
        }
    }

    public function list(Request $request, Item $item)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'price' => ['required', 'numeric', 'min:1', 'max:100000000'],
        ]);

        $user = auth()->user();

        $getItem = Inventory::where('id', '=', request('serial'))->first();

        if($getItem->item_id != $item->id)
        {
            return back()->with('error', 'Item ID mismatch! You cannot resell a collectible from a different collectible\'s page.');
        }

        // check ownership
        if($user->owns($item) && $getItem != null && !$getItem->onsale())
        {
            ItemReseller::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item->id,
                'inventory_id' => request('serial'),
                'price' => request('price'),
            ]);

            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'Serial #'.$getItem->collection_number.' successfully listed on the market.');
        } else {
            return back()->with('error', 'There was an error trying to sell this item.');
        }
    }

    public function unlist(Request $request, Item $item)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'listing' => ['required'],
        ]);

        $user = auth()->user();

        $getItem = ItemReseller::where('id', '=', request('listing'))->first();

        if($getItem->item_id != $item->id)
        {
            return back()->with('error', 'Item ID mismatch! You cannot unlist a collectible from a different collectible\'s page.');
        }

        // check ownership
        if($user->owns($item) && $getItem != null)
        {
            $item = ItemReseller::where([
                ['id', '=', request('listing')],
                ['user_id', '=', $user->id],
            ])->firstOrFail();

            $item->delete();

            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'Serial #'.$getItem->inventory->collection_number.' successfully removed from the market.');
        } else {
            return back()->with('error', 'There was an error trying to take this item offsale.');
        }

    }

    public function buy_listing(Request $request, Item $item, ItemReseller $listing)
    {
        if(Setting::where('market_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'listing' => ['required'],
        ]);

        $user = auth()->user();

        $getItem = ItemReseller::where('id', '=', request('listing'))->first();

        if($getItem->item_id != $item->id)
        {
            return back()->with('error', 'Item ID mismatch! You cannot buy a collectible from a different collectible\'s page.');
        }

        if($getItem != null && (auth()->user()->id != $getItem->user_id))
        {
            if(auth()->user()->cash >= $getItem->price)
            {
                $release = Carbon::now();
                $release = $release->addDay();

                $cashTax = $getItem->price * $item->owner->salesTax();
                $cash = $getItem->price - $cashTax;

                $user->revoke_currency($getItem->price, 1);

                $logPurchase = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'source_id' => $item->id,
                    'source_user' => $getItem->seller->id,
                    'source_type' => '1',
                    'type' => '1',
                    'cash' => $getItem->price,
                ]);
                $logSale = Transaction::create([
                    'user_id' => $getItem->seller->id,
                    'source_id' => $item->id,
                    'source_user' => auth()->user()->id,
                    'source_type' => '1',
                    'type' => '2',
                    'cash' => $cash,
                    'release_at' => $release,
                ]);

                $logData = ItemData::create([
                    'item_id' => $item->id,
                    'price' => $getItem->price,
                ]);

                $inventory = $getItem->inventory;
                $inventory->user_id = auth()->user()->id;
                $inventory->save();

                $getItem->delete();

                $user->action_flood_gate = Carbon::now();
                $user->save();

                return back()->with('success', 'Successfully purchased Serial #'. $getItem->inventory->collection_number.' of ' . $item->name .'!');
            } else {
                return back()->with('error', 'Not enough money!');
            }
        } else {
            return back()->with('error', 'There was an error trying to buy this item.');
        }
    }

    private function generateSerial(): string
    {
        $randomHash = bin2hex(random_bytes(5));
        return $randomHash;
    }
}
