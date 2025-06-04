<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect; // <-- Tambahkan import ini

// Route::get('/', function () { // <-- Baris lama
//     return view('welcome');
// })->name('home');

Route::get('/', function () { // <-- Baris baru
    return Redirect::to('/admin'); // Redirect ke panel admin Filament
})->name('home'); // Nama route 'home' tetap dipertahankan jika Anda membutuhkannya

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
