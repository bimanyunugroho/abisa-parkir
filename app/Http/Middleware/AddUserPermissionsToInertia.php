<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class AddUserPermissionsToInertia
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            Inertia::share('auth.user', [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'permissions' => $request->user()->role != null ? $request->user()->role->permissions->pluck('slug') : ['view-dashboard', 'edit-profile'],
            ]);
        }

        return $next($request);
    }
}