<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.beranda');
            } elseif ($role === 'user') {
                return redirect()->route('user.beranda');
            } else {
                Auth::logout();
                return redirect('/')->with('error', 'Role tidak valid');
            }
        }
        return $next($request);
    }
}
