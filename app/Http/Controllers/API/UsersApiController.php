<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\DiscordUser;
use App\Models\Item;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => 'true',
            'message' => [
                'version' => env('APP_FRAMEWORK'),
                'framework' => '0.0.0',
            ],
        ], 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    public function v1()
    {
        return response()->json([
            'success' => 'false',
            'errors' => [
                'code' => '0',
                'message' => 'Something went wrong with that request, see response status code.'
            ],
        ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    public function discord($discord)
    {
        $get = DiscordUser::where('id', $discord);
        if(!$get->exists()) {
            return response()->json([
                'success' => 'false',
                'errors' => [
                    'code' => '0',
                    'message' => 'Discord user not found.'
                ],
            ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        }
        $get = $get->first();
        $user = User::find($get->user_id);
        return response()->json([
            'success' => $get->exists() ? 'true' : 'false',
            'discord' => [
                'discord_id' => $get->id,
                'user_id' => $get->user_id,
                'username' => $get->username,
                'membership' => $user->membership,
            ],
        ], 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }
}