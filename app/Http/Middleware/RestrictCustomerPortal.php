<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictCustomerPortal
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->role === 'customer' && ! $this->isAllowedRoute($request)) {
            abort(403, 'Customer hanya dapat mengakses dashboard, pembayaran, dan laporan kerusakan.');
        }

        return $next($request);
    }

    private function isAllowedRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        return $routeName === 'dashboard'
            || $routeName === 'logout'
            || $routeName === 'documents.ktp'
            || str_starts_with((string) $routeName, 'client.');
    }
}
