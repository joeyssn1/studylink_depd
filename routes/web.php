<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/study', function () {
    return view('studypage');
});

Route::get('/pomodoro', function () {
    return view('pomodoro');
});

Route::get('/active-recall', function () {
    return view('recall');
});

Route::get('/spaced-repetition', function () {
    return view('repetition');
});