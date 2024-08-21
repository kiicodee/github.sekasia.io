<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("register",[AuthController::class,"register"]);
        Route::post("login",[AuthController::class,"login"]);

        Route::middleware("auth:sanctum")->group(function () {
             Route::post("logout",[AuthController::class,"logout"]);
        });
    });

    Route::middleware("auth:sanctum")->group(function () {
        // post
        Route::post("posts",[PostController::class,"store"]);
        Route::get("posts",[PostController::class,"show"]);
        Route::delete("posts/{id}",[PostController::class,"delete"]);

        Route::prefix("users")->group(function () {
            Route::get("",[UserController::class,"user"]);
            Route::get('{username}',[UserController::class,"userDetail"]);

        // follow
            Route::post('{username}/follow', [UserController::class,'follow']);
            Route::delete('{username}/unfollow', [UserController::class,'unfollow']);
            Route::get('{username}/following', [UserController::class,'following']); 
            Route::get('{username}/followers', [UserController::class,'follower']); 

            Route::put('{username}/accept', [UserController::class,'accept']); 
        });
        
   });

});
