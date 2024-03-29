<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[PostController::class,'index'])->name('posts.index');
Route::middleware(['auth'])->group(function(){
    
    Route::resource('posts',PostController::class)->except('index');
    
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function(){

    Route::resource('categories',CategoryController::class);
});

// Route::get('/',[PostController::class,'category'])->name('posts.category');

require __DIR__.'/auth.php';
