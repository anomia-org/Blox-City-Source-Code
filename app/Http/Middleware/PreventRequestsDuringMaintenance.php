<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Maintenance;
use Illuminate\Support\Facades\Artisan;

class PreventRequestsDuringMaintenance extends Maintenance
{
    protected $excludedIPs = [
        '97.92.205.231', //kyle
        '94.7.159.20', //Aurick
        '188.65.242.165', //studzy
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->maintenanceMode()->active()) {
            $data = $this->app->maintenanceMode()->data();

            if (isset($data['secret']) && $request->path() === $data['secret']) {
                return $this->bypassResponse($data['secret']);
            }

            if ($this->hasValidBypassCookie($request, $data) ||
                $this->inExceptArray($request) ||
                in_array($request->ip(), $this->excludedIPs)) {
                return $next($request);
            }

            if (isset($data['redirect'])) {
                $path = $data['redirect'] === '/'
                            ? $data['redirect']
                            : trim($data['redirect'], '/');

                if ($request->path() !== $path) {
                    return redirect($path);
                }
            }
        }
        
        $check = Setting::where('maintenance_enabled', '1')->get()->first();
        if($check && !app()->isDownForMaintenance()) 
            Artisan::call('down --secret="sIDrDjIEIsLfJicCspWWKVtxPhlbBxeEXfyoZxIjYlslfgaCUvfxky" --render="maintenance/index" --redirect="/site/offline" --retry=120');            
        if(!$check && app()->isDownForMaintenance())
            Artisan::call('up');

        return $next($request);
    }
}