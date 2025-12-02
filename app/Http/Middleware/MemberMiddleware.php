<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/member/login');
        }

        if (!Auth::user()->isMember()) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}






