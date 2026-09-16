<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        if (! in_array($user->role, $roles, true)) {
            $home = match ($user->role) {
                'admin' => '/admin',
                'staff' => '/staff',
                default => '/student',
            };

            return redirect($home);
        }

        return $next($request);
    }
}
