<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth & Profile
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\BerandaController as AdminBerandaController;
use App\Http\Controllers\Admin\WargaController as AdminWargaController;
use App\Http\Controllers\Admin\KartuKeluargaController as AdminKKController;
use App\Http\Controllers\Admin\PerpindahanController as AdminPerpindahanController;
use App\Http\Controllers\Admin\PendatangController as AdminPendatangController;
use App\Http\Controllers\Admin\KelahiranController as AdminKelahiranController;
use App\Http\Controllers\Admin\KematianController as AdminKematianController;

// User Controllers
use App\Http\Controllers\User\BerandaController as UserBerandaController;
use App\Http\Controllers\User\WargaController as UserWargaController;
use App\Http\Controllers\User\KartuKeluargaController as UserKartuKeluargaController;
use App\Http\Controllers\User\PerpindahanController as UserPerpindahanController;
use App\Http\Controllers\User\PendatangController as UserPendatangController;
use App\Http\Controllers\User\KelahiranController as UserKelahiranController;
use App\Http\Controllers\User\KematianController as UserKematianController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Awal
Route::get('/', function () {
    return view('auth/login');
});

// Auth Routes (Login, Register, etc.)
require __DIR__ . '/auth.php';

// Redirect berdasarkan role setelah login
Route::get('/redirect-by-role', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.beranda');
    } elseif ($user->role === 'user') {
        return redirect()->route('user.beranda');
    } else {
        Auth::logout();
        return redirect('/')->with('error', 'Role tidak valid.');
    }
})->middleware('auth');


// ROUTE UNTUK USER 
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/beranda', [UserBerandaController::class, 'index'])->name('beranda');

    Route::resource('warga', UserWargaController::class);
    Route::resource('kartukeluarga', UserKartuKeluargaController::class);
    Route::resource('perpindahan', UserPerpindahanController::class);
    Route::resource('pendatang', UserPendatangController::class);
    Route::resource('kelahiran', UserKelahiranController::class);
    Route::resource('kematian', UserKematianController::class);

    Route::get('warga/export/pdf', [UserWargaController::class, 'exportPdfWithKop'])->name('warga.exportPdf');
    Route::get('warga/export/excel', [UserWargaController::class, 'exportExcelWithKop'])->name('warga.exportExcel');
    Route::get('kartukeluarga/export/pdf', [UserKartuKeluargaController::class, 'exportPdfWithKop'])->name('kartukeluarga.exportPdf');
    Route::get('kartukeluarga/export/excel', [UserKartuKeluargaController::class, 'exportExcelWithKop'])->name('kartukeluarga.exportExcel');
    Route::get('perpindahan/export/pdf', [UserPerpindahanController::class, 'exportPdfWithKop'])->name('perpindahan.exportPdf');
    Route::get('perpindahan/export/excel', [UserPerpindahanController::class, 'exportExcelWithKop'])->name('perpindahan.exportExcel');
    Route::get('pendatang/export/pdf', [UserPendatangController::class, 'exportPdfWithKop'])->name('pendatang.exportPdf');
    Route::get('pendatang/export/excel', [UserPendatangController::class, 'exportExcelWithKop'])->name('pendatang.exportExcel');
    Route::get('kelahiran/export/pdf', [UserKelahiranController::class, 'exportPdfWithKop'])->name('kelahiran.exportPdf');
    Route::get('kelahiran/export/excel', [UserKelahiranController::class, 'exportExcelWithKop'])->name('kelahiran.exportExcel');
    Route::get('kematian/export/pdf', [UserKematianController::class, 'exportPdfWithKop'])->name('kematian.exportPdf');
    Route::get('kematian/export/excel', [UserKematianController::class, 'exportExcelWithKop'])->name('kematian.exportExcel');
    
});

// ROUTE UNTUK ADMIN 
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/beranda', [AdminBerandaController::class, 'index'])->name('beranda');

    Route::resource('warga', AdminWargaController::class);
    Route::resource('kartukeluarga', AdminKKController::class);
    Route::resource('perpindahan', AdminPerpindahanController::class);
    Route::resource('pendatang', AdminPendatangController::class);
    Route::resource('kelahiran', AdminKelahiranController::class);
    Route::resource('kematian', AdminKematianController::class);
});

// ROUTE PROFILE PENGGUNA
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// LOGOUT 
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
