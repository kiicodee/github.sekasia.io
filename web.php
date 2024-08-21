<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});


// login
Route::get('login', [AuthController::class,'loginview']);
Route::post('login', [AuthController::class,'login']);


Route::get('home', [HomeController::class,'home']);

