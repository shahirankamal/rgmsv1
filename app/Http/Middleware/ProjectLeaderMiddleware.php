<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectLeaderMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isProjectLeader()) {
            abort(403, 'Unauthorized action. Only project leaders can access this resource.');
        }

        return $next($request);
    }
} 