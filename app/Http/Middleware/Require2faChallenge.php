<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Require2faChallenge
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('2fa:user_id')) {
            return redirect()->route('login')->withErrors(['username' => 'กรุณาเข้าสู่ระบบก่อน']);
        }
        return $next($request);
    }
}
