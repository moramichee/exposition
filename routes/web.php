<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ExhibitionElementController as AdminExhibitionElementController;
use App\Http\Controllers\ExhibitionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExhibitionController::class, 'home'])->name('home');
Route::get('/about', [ExhibitionController::class, 'about'])->name('about');
Route::get('/artists', [ExhibitionController::class, 'artists'])->name('artists');
Route::get('/venue', [ExhibitionController::class, 'venue'])->name('venue');
Route::get('/press', [ExhibitionController::class, 'press'])->name('press');
Route::get('/contact', [ExhibitionController::class, 'contact'])->name('contact');
Route::post('/register', [ExhibitionController::class, 'register'])->name('register');

Route::get('/media/press-release.pdf', [ExhibitionController::class, 'pressReleasePdf'])
    ->name('press.release.pdf');

Route::prefix('/admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin.auth')->group(function (): void {
        Route::get('/', fn () => redirect()->route('admin.elements.index'))->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::resource('/elements', AdminExhibitionElementController::class)->except(['show']);
    });
});

Route::prefix('/legal')->name('legal.')->group(function (): void {
    Route::get('/privacy-policy', [ExhibitionController::class, 'privacy'])->name('privacy');
    Route::get('/terms-and-conditions', [ExhibitionController::class, 'terms'])->name('terms');
    Route::get('/cookie-notice', [ExhibitionController::class, 'cookies'])->name('cookies');
    Route::get('/copyright', [ExhibitionController::class, 'copyright'])->name('copyright');
});
