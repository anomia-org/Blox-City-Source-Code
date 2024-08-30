<?php

namespace App\Http\Middleware;

use App\Models\Ip;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class logIps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check())
        {
            $user = Auth::user();

            $get = Ip::where('user_id', '=', $user->id)->where('ip', '=', $_SERVER['REMOTE_ADDR'])->get()->first();

            if($get) {
                if(Carbon::parse($get->last_used)->diffInMinutes(now()) >= 15)
                {
                    $get->last_used = Carbon::now();
                    $get->save();
                }
                //Ip::where('user_id', '=', $user->id)->where('ip', '=', $_SERVER['REMOTE_ADDR'])->update(['last_used' => Carbon::now()]);
            } else {
                Ip::insert([
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'user_id' => $user->id,
                    'last_used' => Carbon::now(),
                ]);
            }

            DB::insert('insert into requests (user_id, page, created_at, updated_at) values (?, ?, ?, ?)', [$user->id, $request->url(), Carbon::now(), Carbon::now()]);

        }
        return $next($request);
    }
}
