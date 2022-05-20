<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;

class check2fa
{

    public function handle(Request $request, Closure $next)
    {
        if (!\Illuminate\Support\Facades\Session::has('user_2fa')) {
            return redirect()->route('2fa.index');
        }
        return $next($request);
    }
}
