<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user === null) {
            abort(401);
        }

        if (! (bool) ($user->is_admin ?? false)) {
            abort(403, 'Admin privileges are required.');
        }

        return $next($request);
    }
}

