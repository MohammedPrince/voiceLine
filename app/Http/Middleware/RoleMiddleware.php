<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $allowed = explode('|', $roles);
        if (! $request->user() || ! in_array($request->user()->role, $allowed, true)) {
            abort(403, 'غير مصرح بالدخول.');
        }
        return $next($request);
    }
}
