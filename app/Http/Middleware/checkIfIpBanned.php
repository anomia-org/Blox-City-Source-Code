<?php

namespace App\Http\Middleware;

use App\Models\IpBan;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class checkIfIpBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $banned = IpBan::where('ip', '=', $_SERVER['REMOTE_ADDR'])->where('active', '=', '1')->get()->first();

        if($banned)
        {
            if(Carbon::parse($banned->expires_at) > now())
            {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'code' => '403',
                        'message' => 'Your internet protocol (IP) address has been banned from accessing BLOX City and any related services. If you believe this is a mistake, please contact our support team.',
                    ],
                ], 403, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            } else {
                $banned->active = 0;
                $banned->save();
                redirect(route('index'));
            }
        }

        return $next($request);
    }
}
