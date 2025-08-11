<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        $request->session()->regenerate();

        session()->flash('success', 'Login berhasil! Selamat datang, ' . Auth::user()->name);
                
        // Redirect berdasarkan role
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin/beranda');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->intended('/user/beranda');
        }

        // Jika role tidak dikenal
        Auth::logout();
        return redirect('/')->with('error', 'Role tidak valid');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah',
    ]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
