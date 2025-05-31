<?php
use Illuminate\Support\Facades\Route;
use Leazycms\EArsip\Controllers\WebController;
use Leazycms\Web\Http\Controllers\Auth\LoginController;
Route::get('/', [WebController::class, 'home']);
Route::get('/login', [LoginController::class, 'loginForm']);
Route::post('/login', [LoginController::class, 'loginSubmit']);
Route::match(['get','post'],'/logout', [LoginController::class, 'logout']);
Route::get('termasuk', [WebController::class, 'termasuk']);
