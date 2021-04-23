<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class handleInertia
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

        if (File::exists($mixManifestFile = public_path('mix-manifest.json'))) {
            Inertia::version(function () use ($mixManifestFile) {
                return md5_file($mixManifestFile);
            });
        }

        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $permissions = Cache::remember('permissions', 1, function () {
            return Permission::pluck('name');
        });

        $abilities = [];
        foreach ($permissions as $perm) {
            $abilities[$perm] = $user->can($perm);
        }

        Inertia::share([
            'auth' => function () use ($abilities) {
                return [
                    'can' => $abilities
                ];
            },
            'notifications' => $user->notifications,
            'previous'  => url()->previous(),
        ]);

        Inertia::share(['message' => session('message')]);

        // return $response;
        return $next($request);
    }
}
