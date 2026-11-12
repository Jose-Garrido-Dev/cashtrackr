<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Route::get('/', function () {
//     return view('welcome');  método closure
// });

Route::get('/auth/register', [RegisterController::class,'index'])->name('register');
Route::post('/auth/register', [RegisterController::class,'store'])->name('register.store');


Route::get('/auth/login',[LoginController::class, 'index'] )->name('login');