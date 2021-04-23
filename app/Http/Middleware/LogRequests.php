<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        \Log::info('');
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();

            \DB::table('users')->where('id', $user->id)
                ->update(
                    ['last_login' => Carbon::now()->toDateTimeString(), 'last_login_ip' => $request->ip()]
                );

            \Log::useFiles(storage_path('logs/audit.log'));
            \Log::info('API Request - ' . $user->email . ' at IP - ' . $request->ip() . ' via ' . $request->method() . ' to ' . $request->fullUrl() . ' using ' . $request->userAgent());
        } elseif (Auth::guard('web')->check()) {
            $webUser = Auth::guard('web')->user();

            \DB::table('users')->where('id', $webUser->id)
                ->update(
                    ['last_login' => Carbon::now()->toDateTimeString(), 'last_login_ip' => $request->ip()]
                );

            \Log::info('WEB Request - ' . $webUser->email . ' at IP - ' . $request->ip() . ' via ' . $request->method() . ' request to ' . $request->fullUrl() . ' using ' . $request->userAgent());
        } else {
            \Log::info('WEB Request - at IP - ' . $request->ip() . ' via ' . $request->method() . ' request to ' . $request->fullUrl() . ' from ' . $request->ip() . ' using ' . $request->userAgent());
        }

        return $response;
    }
}
