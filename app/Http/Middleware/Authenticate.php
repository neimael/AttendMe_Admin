<?php

namespace App\Http\Middleware;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware {
protected function redirectTo(Request $request): ?string
{
    return $request->expectsJson() ? null : route('login');

}
}
