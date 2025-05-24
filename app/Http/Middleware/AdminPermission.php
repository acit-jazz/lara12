<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class AdminPermission
{
    public function handle($request, Closure $next, $rolesOrPermissions)
    {
        $user = auth('admin')->user();

        if (! $user) {
            abort(403, 'User is not logged in');
        }

        $rolesOrPermissions = explode('|', $rolesOrPermissions);

        foreach ($rolesOrPermissions as $roleOrPermission) {
            // Cek role dulu
            // if ($user->hasRole($roleOrPermission)) {
            //     return $next($request);
            // }

            // Kalau bukan role, cek permission (tangani exception jika permission tidak ada)
            try {
                if ($user->hasPermissionTo($roleOrPermission)) {
                    return $next($request);
                }
            } catch (PermissionDoesNotExist $e) {
                // Permission tidak ada, lanjut ke item berikutnya
            }
        }

        throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
    }
}
