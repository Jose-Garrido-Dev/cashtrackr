<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('welcome');  método closure
// });

Route::get('/auth/register', [RegisterController::class,'index'])->name('register');
Route::post('/auth/register', [RegisterController::class,'store'])->name('register.store');


Route::get('/auth/login',[LoginController::class, 'index'] )->name('login');
Route::post('/auth/login', [LoginController::class,'store'])->name('login.store');

Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request){
    $request->fulfill(); // con este metodo toma los valores de la url y trata de confirmar la cuenta

    return redirect()->route('dashboard')->with('success','Tu correo fue verificado correctamente, ya puedes crear presupuestos y gastos');
})->middleware(['auth','signed'])->name('verification.verify');

route::get('/email/verify',function(){
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

route::post('/email/verification-notification', function(Request $request){

    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Se ha reenviado el correo de verificación');
})->middleware('auth','throttle:2,1')->name('verification.send'); // esta funcion throttle son la cantidad de peticiones que puedo enviar dice 2 peticiones en 1 minuto

route::get('/dashboard', function(){
        return view('dashboard');
})->middleware(['auth','verified'])->name('dashboard');