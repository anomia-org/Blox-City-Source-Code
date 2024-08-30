<?php

namespace App\Http\Controllers\AIS;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use App\Models\AntelopeLog;

class CreateItemsController extends Controller
{
    public function index($type)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        switch ($type) {
            case 'hat':
                $title = 'Create New Hat';

                if (auth()->user()->power < 3) abort(404);
                break;
            case 'face':
                $title = 'Create New Face';

                if (auth()->user()->power < 3) abort(404);
                break;
            case 'tool':
                $title = 'Create New Tool';

                if (auth()->user()->power < 3) abort(404);
                break;
            case 'head':
                $title = 'Create New Head';

                if (auth()->user()->power < 3) abort(404);
                break;
            default:
                abort(404);
        }

        return view('ais.create_item')->with([
            'title' => $title,
            'type' => $type
        ]);
    }

    public function create(Request $request)
    {
        if((auth()->user()->power <= 0) || (empty(Cookie::get('ais'))) || (Cookie::get('ais') != auth()->user()->ais))
        {
            return redirect(route('login'));
        }

        if (
            !in_array($request->type, ['hat', 'face', 'tool', 'head']) ||
            (auth()->user()->power < 3 && $request->type == 'hat') ||
            (auth()->user()->power < 3 && $request->type == 'face') ||
            (auth()->user()->power < 3 && $request->type == 'tool') ||
            (auth()->user()->power < 3 && $request->type == 'head')
        ) abort(404);

        $collectible = $request->has('collectible');

        $realName = bin2hex(random_bytes(32));
        if(request()->hasFile('texture'))
            $textureName = ($request->type == 'face') ? $realName.'.'.request()->texture->getClientOriginalExtension() : request()->texture->getClientOriginalName();
        if(request()->hasFile('material'))
            $materialName = $request->hasFile('material') ? request()->material->getClientOriginalName() : "";
        if(request()->hasFile('model'))
            $modelName = $request->hasFile('model') ? request()->model->getClientOriginalName() : "";

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/cdn',
        ]);

        //$disk->putFileAs('', $request->texture, $imageName);

        //$textureName = ($request->type == 'hat') ? generate_filename() : Str::random(50);
        $filename = "";
        $validate = [];

        $validate['name'] = ['required', 'min:1', 'max:70'];
        $validate['description'] = ['max:1024'];

        if ($request->type != 'head') {
            $validate['texture'] = ($request->type != 'face') ? ['mimes:png,jpg,jpeg', 'max:2048', 'nullable'] : ['required', 'mimes:png,jpg,jpeg', 'max:2048'];
            $validate['material'] = ['mimes:txt', 'max:2048'];
        }

        if ($request->type != 'face')
            $validate['model'] = ['required', 'mimes:txt', 'max:2048'];

        $validate['coins'] = ['required', 'numeric', 'min:-1', 'max:1000000'];
        $validate['cash'] = ['required', 'numeric', 'min:-1', 'max:1000000'];

        if ($collectible)
            $validate['stock'] = ['required', 'numeric', 'min:0', 'max:500'];

        $this->validate($request, $validate);

        switch ($request->onsale_for) {
            case '1_hour':
                $time = 3600;
                break;
            case '12_hours':
                $time = 43200;
                break;
            case '1_day':
                $time = 86400;
                break;
            case '3_days':
                $time = 259200;
                break;
            case '7_days':
                $time = 604800;
                break;
            case '14_days':
                $time = 1209600;
                break;
            case '21_days':
                $time = 1814400;
                break;
            case '1_month':
                $time = 2592000;
                break;
        }

        switch ($request->type) {
            case 'hat':
                $type = 1;
                break;
            case 'tool':
                $type = 3; 
                break;
            case 'face':
                $type = 2;
                break;
            case 'box':
                $type = 7;
                break;
        }

        $item = new Item;
        $item->creator_id = 1;
        $item->name = $request->name;
        $item->desc = $request->description;
        $item->type = $type;
        $item->pending = 0;
        $item->coins = $request->coins;
        $item->cash = $request->cash;
        $item->special = ($collectible) ? 1 : 0;
        $item->stock_limit = ($collectible) ? $request->stock : 0;
        $item->source = ($request->type == 'face') ? $realName : pathinfo(request()->model->getClientOriginalName(), PATHINFO_FILENAME);
        $item->offsale_at = isset($time) ? Carbon::createFromTimestamp(time() + $time)->format('Y-m-d H:i:s') : null;
        $item->hash = ($request->type == 'face') ? $realName : "no";
        $item->sales = 0;
        $item->save();

        AntelopeLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->username . ' created official item ID# ' . $item->id,
        ]);


        if ($request->type != 'head') {
            if ($request->hasFile('texture'))
                $disk->putFileAs('', $request->file('texture'), $textureName);

            if ($request->hasFile('material'))
                $disk->putFileAs('', $request->file('material'), $materialName);
        }

        if ($request->type != 'face')
            $disk->putFileAs('', $request->file('model'), $modelName);

        if ($request->type != 'face')
            app('App\Http\Controllers\API\AvatarsController')->market($item);
        if($item->cash > 0 || $item->coins > 0 || $item->free())
            $item->notifyWebhooks(true);

        return redirect()->route('market.item', $item->id);
    }
}
