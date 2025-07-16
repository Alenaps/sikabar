<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
   public function authenticated(Request $request, $user)
{
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else if ($user->role === 'user') {
        return redirect()->route('user.dashboard');
    } else {
        Auth::logout();
        return redirect()->route('login')->with('error', 'Role tidak valid');
    }
}

}
