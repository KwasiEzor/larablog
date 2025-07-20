<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        // Show default dashboard for common users
        return view('dashboard');
    })->name('dashboard');

    // Admin dashboard route - only for admin/author users
    Route::get('/admin-dashboard', function () {
        // Check if user has admin or author role
        if (Auth::check() && Auth::user()->hasRole(['admin', 'author'])) {
            return redirect('/admin');
        }
        // Redirect unauthorized users back to regular dashboard
        return redirect()->route('dashboard');
    })->name('admin.panel');
});

// Note: Filament handles the /admin route directly through AdminPanelProvider
// No custom routes needed for the admin panel
