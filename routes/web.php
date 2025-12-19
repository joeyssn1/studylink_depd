<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Models\Event;

Route::get('/', function () {

    if (!Auth::check()) {
        return view('homepage', ['joinedEvents' => []]);
    }

    $events = Auth::user()
        ->joinedEvents
        ->groupBy('date')
        ->map(function ($items) {
            return $items->map(function ($event) {
                return [
                    'event_name' => $event->event_name,
                    'time' => $event->time,
                    'description' => $event->description,
                ];
            });
        });

    return view('homepage', [
        'joinedEvents' => $events
    ]);
})->name('home');


Route::get('/study', fn() => view('studypage'));
Route::get('/pomodoro', fn() => view('pomodoro'));
Route::get('/active-recall', fn() => view('recall'));
Route::get('/spaced-repetition', fn() => view('repetition'));

Route::post('/join-event', [EventController::class, 'joinByCode'])
    ->middleware('auth')
    ->name('events.join');

Route::get('/profile', function () {
    return view('profilepage', [
        'events' => Event::where('user_id', Auth::id())->get()
    ]);
})->middleware('auth')->name('profile');


Route::get('/login', [AuthController::class, 'loginPage'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::get('/register', [AuthController::class, 'registerPage'])
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth');

Route::post('/events', [EventController::class, 'store'])
    ->middleware('auth');
