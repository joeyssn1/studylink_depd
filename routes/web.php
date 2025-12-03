<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public pages
Route::get('/', function () { return view('homepage'); });
Route::get('/study', function () { return view('studypage'); });
Route::get('/pomodoro', function () { return view('pomodoro'); });
Route::get('/active-recall', function () { return view('recall'); });
Route::get('/spaced-repetition', function () { return view('repetition'); });

// Auth pages
Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerPage']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
