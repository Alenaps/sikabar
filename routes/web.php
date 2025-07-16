<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\User\BerandaController as UserBerandaController;
use App\Http\Controllers\Admin\BerandaController as AdminBerandaController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/beranda', [UserBerandaController::class, 'index'])->name('beranda');
});
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/beranda', [AdminBerandaController::class, 'index'])->name('beranda');
});

Route::get('/redirect-by-role', function () {
    if (auth()->user()->role === 'admin') {
        return redirect('/admin/beranda');
    } elseif (auth()->user()->role === 'user') {
        return redirect('/user/beranda');
    } else {
        Auth::logout();
        return redirect('/')->with('error', 'Role tidak valid');
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
