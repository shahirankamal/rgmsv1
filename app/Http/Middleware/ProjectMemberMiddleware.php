<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user() || !Auth::user()->academician) {
            return redirect('/login');
        }

        // Check if user is a member of any grants
        $isMember = Auth::user()->academician->memberGrants()->count() > 0;
        
        if (!$isMember) {
            return redirect()->route('home')
                ->with('error', 'Unauthorized action.');
        }

        return $next($request);
    }
} 