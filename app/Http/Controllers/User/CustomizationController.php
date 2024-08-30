<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    public function index()
    {
        return view('user.customize');
    }

    public function orient(Request $request)
    {
        if($request->has('left'))
        {
            if(auth()->user()->avatar->orient != 1) {

                $avatar = auth()->user()->avatar;
                $avatar->orient = 1;
                $avatar->save();
                app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully updated avatar orientation!');
            } else {
                return back();
            }
        } elseif($request->has('right')) {
            if(auth()->user()->avatar->orient != 2) {

                $avatar = auth()->user()->avatar;
                $avatar->orient = 2;
                $avatar->save();
                app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully updated avatar orientation!');
            } else {
                return back();
            }
        } else {
            return abort('404');
        }
    }

    public function avatar(Request $request)
    {
        if($request->has('humanoid'))
        {
            if(auth()->user()->avatar->avatar != 2) {

                $avatar = auth()->user()->avatar;
                $avatar->avatar = 2;
                $avatar->save();
                app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully updated avatar type!');
            } else {
                return back();
            }
        } elseif($request->has('blocky')) {
            if(auth()->user()->avatar->avatar != 1) {

                $avatar = auth()->user()->avatar;
                $avatar->avatar = 1;
                $avatar->save();
                app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully updated avatar type!');
            } else {
                return back();
            }
        } else {
            return abort('404');
        }
    }

    public function color(Request $request)
    {
        if(request('chosePart') != '') {
            switch (request('chosePart')) {
                case 'head':
                    $part = 'hex_'.request('chosePart');
                    break;
                case 'torso':
                    $part = 'hex_'.request('chosePart');
                    break;
                case 'rarm':
                    $part = 'hex_'.request('chosePart');
                    break;
                case 'larm':
                    $part = 'hex_'.request('chosePart');
                    break;
                case 'rleg':
                    $part = 'hex_'.request('chosePart');
                    break;
                case 'lleg':
                    $part = 'hex_'.request('chosePart');
                    break;
                default:
                    return abort(404);
                    break;
            }

            if(!$request->has('chooser'))
            {
                $this->validate($request, [
                    'hColor' => ['required', 'regex:/#[a-zA-Z0-9]{6}$/'],
                ]);

                $color = str_replace('#', '', request('hColor'));

                $avatar = auth()->user()->avatar;
                if($part == 'hex_head') {
                    $avatar->hex_head = $color;
                } elseif ($part == 'hex_torso') {
                    $avatar->hex_torso = $color;
                } elseif ($part == 'hex_larm') {
                    $avatar->hex_larm = $color;
                } elseif ($part == 'hex_rarm') {
                    $avatar->hex_rarm = $color;
                } elseif ($part == 'hex_lleg') {
                    $avatar->hex_lleg = $color;
                } elseif ($part == 'hex_rleg') {
                    $avatar->hex_rleg = $color;
                }

                $user = auth()->user();

                $avatar->save();
                app('App\Http\Controllers\API\AvatarsController')->render($user);

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully updated body part color!');
            } else {
                if(request('customColor') != '') {
                    $this->validate($request, [
                        'customColor' => ['required', 'regex:/#[a-zA-Z0-9]{6}$/'],
                    ]);

                    $color = str_replace('#', '', request('customColor'));

                    $avatar = auth()->user()->avatar;
                    if($part == 'hex_head') {
                        $avatar->hex_head = $color;
                    } elseif ($part == 'hex_torso') {
                        $avatar->hex_torso = $color;
                    } elseif ($part == 'hex_larm') {
                        $avatar->hex_larm = $color;
                    } elseif ($part == 'hex_rarm') {
                        $avatar->hex_rarm = $color;
                    } elseif ($part == 'hex_lleg') {
                        $avatar->hex_lleg = $color;
                    } elseif ($part == 'hex_rleg') {
                        $avatar->hex_rleg = $color;
                    }

                    $avatar->save();
                    app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                    $flood = auth()->user();
                    $flood->action_flood_gate = Carbon::now();
                    $flood->save();

                    return back()->with('success', 'Successfully updated body part color!');
                } else {
                    return back()->with('error', 'The color field was empty.');
                }
            }
        } else {
            if(!$request->has('chooser'))
            {
                $this->validate($request, [
                    'hColor' => ['required', 'regex:/#[a-zA-Z0-9]{6}$/'],
                ]);

                $color = str_replace('#', '', request('hColor'));

                $avatar = auth()->user()->avatar;

                $avatar->hex_head = $color;
                $avatar->hex_torso = $color;
                $avatar->hex_larm = $color;
                $avatar->hex_rarm = $color;
                $avatar->hex_lleg = $color;
                $avatar->hex_rleg = $color;

                $user = auth()->user();

                $avatar->save();
                app('App\Http\Controllers\API\AvatarsController')->render($user);

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();

                return back()->with('success', 'Successfully updated body part color!');
            } else {
                if(request('customColor') != '') {
                    $this->validate($request, [
                        'customColor' => ['required', 'regex:/#[a-zA-Z0-9]{6}$/'],
                    ]);

                    $color = str_replace('#', '', request('customColor'));

                    $avatar = auth()->user()->avatar;
                    $avatar->hex_torso = $color;
                    $avatar->hex_larm = $color;
                    $avatar->hex_rarm = $color;
                    $avatar->hex_lleg = $color;
                    $avatar->hex_rleg = $color;

                    $avatar->save();
                    app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                    $flood = auth()->user();
                    $flood->action_flood_gate = Carbon::now();
                    $flood->save();

                    return back()->with('success', 'Successfully updated body part color!');
                } else {
                    return back()->with('error', 'The color field was empty.');
                }
            }
        }
    }

    public function equip(Item $item)
    {
        if($item->exists)
        {
            if(auth()->user()->owns($item))
            {
                if(!auth()->user()->isWearing($item))
                {

                    if($item->pending != 0)
                    {
                        return back()->with('error', 'Item is not approved!');
                    }

                    $avatar = auth()->user()->avatar;
                    if($item->type == 1) {
                        if($avatar->hat1_id != 0) { //if hat1 has an item, we check hat2
                            if($avatar->hat2_id != 0) { //if hat2 has an item, we check hat3
                                if($avatar->hat3_id != 0) { //if hat3 has an item, we cycle back and reset hat1 to what they are requesting to wear
                                    $avatar->hat1_id = $item->id;
                                } else {
                                    $avatar->hat3_id = $item->id; //otherwise we set hat3 to requested item
                                }
                            } else {
                                $avatar->hat2_id = $item->id; //otherwise we set hat2 to requested item
                            }
                        } else {
                            $avatar->hat1_id = $item->id; //otherwise we set hat1 to requested item
                        }
                    } elseif ($item->type == 2) {
                        $avatar->face_id = $item->id;
                    } elseif ($item->type == 3) {
                        $avatar->tool_id = $item->id;
                    } elseif ($item->type == 4) {
                        $avatar->shirt_id = $item->id;
                    } elseif ($item->type == 5) {
                        $avatar->pants_id = $item->id;
                    } elseif ($item->type == 6) {
                        $avatar->tshirt_id = $item->id;
                    }

                    $avatar->save();

                    app('App\Http\Controllers\API\AvatarsController')->render(auth()->user());

                    $flood = auth()->user();
                    $flood->action_flood_gate = Carbon::now();
                    $flood->save();
                  //  return back()->with('success', 'Successfully updated avatar!');
                }// else {
                  //  return back()->with('error', 'You\'re already wearing that item!');
              //  }
            }// else {
             //   return back()->with('error', 'You don\'t own this item!');
           // }
        }// else {
         //  return abort('404');
        //}
    }

    public function unequip(Item $item)
    {
        if($item->exists)
        {
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

                $flood = auth()->user();
                $flood->action_flood_gate = Carbon::now();
                $flood->save();
               // return back()->with('success', 'Successfully updated avatar!');
            } //else {
             //   return back()->with('error', 'You\'re not wearing that item!');
            //}
        } //else {
          //  return abort(404);
        //}
    }

    //#[a-zA-Z0-9]{6} regex for hex codes for custom color chooser
}
