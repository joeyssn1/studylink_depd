<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StudyController;

use App\Models\Event;
use App\Models\StudyCounting;

/*
|--------------------------------------------------------------------------
| HOMEPAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    // Guest user
    if (!Auth::check()) {
        return view('homepage', [
            'joinedEvents'   => [],
            'pomodoro_count' => 0,
            'active_count'   => 0,
        ]);
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // A. Events created by user
    $createdEvents = Event::where('user_id', $user->id)->get();

    // B. Events joined by user
    $joinedEvents = $user->joinedEvents()->get();

    // C. Merge & remove duplicates
    $allEvents = $createdEvents
        ->merge($joinedEvents)
        ->unique('id');

    // D. Format for calendar JS
    $formattedEvents = $allEvents
        ->groupBy('date')
        ->map(function ($items) {
            return $items->map(function ($event) {
                return [
                    'id'           => $event->id,
                    'user_id'      => $event->user_id,
                    'event_name'   => $event->event_name,
                    'date'         => $event->date,
                    'start_time'   => $event->start_time,
                    'end_time'     => $event->end_time,
                    'description' => $event->description,
                ];
            });
        });

    // Study statistics
    $stats = StudyCounting::where('user_id', $user->id)->first();

    return view('homepage', [
        'joinedEvents'   => $formattedEvents,
        'pomodoro_count' => $stats->pomodoro_count ?? 0,
        'active_count'   => $stats->active_count ?? 0,
    ]);
})->name('home');


/*
|--------------------------------------------------------------------------
| STUDY (ENTRY POINT)
|--------------------------------------------------------------------------
*/
Route::get('/study', fn() => view('studypage'))
    ->middleware('auth')
    ->name('studypage');

Route::post('/study/store', [StudyController::class, 'store'])
    ->middleware('auth')
    ->name('study.store');


/*
|--------------------------------------------------------------------------
| POMODORO
|--------------------------------------------------------------------------
*/
Route::get('/pomodoro/{id}', [StudyController::class, 'pomodoroPage'])
    ->middleware('auth')
    ->name('pomodoro.show');

Route::post('/pomodoro/store', [StudyController::class, 'storePomodoro'])
    ->middleware('auth')
    ->name('pomodoro.store');

Route::post('/pomodoro/save', [StudyController::class, 'savePomodoroState'])
    ->middleware('auth')
    ->name('pomodoro.save');

Route::get('/pomodoro/complete/{id}', [StudyController::class, 'completePomodoro'])
    ->middleware('auth')
    ->name('pomodoro.complete');


/*
|--------------------------------------------------------------------------
| ACTIVE RECALL
|--------------------------------------------------------------------------
*/
Route::get('/active-recall/{id}', [StudyController::class, 'activeRecallPage'])
    ->middleware('auth')
    ->name('active-recall.show');

Route::post('/material/upload', [StudyController::class, 'uploadMaterial'])
    ->middleware('auth')
    ->name('material.upload');

Route::post('/active-recall/generate', [StudyController::class, 'generateQuestions'])
    ->middleware('auth')
    ->name('recall.generate');

Route::post('/active-recall/submit', [StudyController::class, 'submitAnswer'])
    ->middleware('auth')
    ->name('recall.submit');


/*
|--------------------------------------------------------------------------
| HISTORY
|--------------------------------------------------------------------------
*/
Route::get('/history', [StudyController::class, 'history'])
    ->middleware('auth')
    ->name('study.history');

Route::delete('/study/{id}', [StudyController::class, 'destroy'])
    ->middleware('auth')
    ->name('study.delete');


/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/
Route::post('/join-event', [EventController::class, 'joinByCode'])
    ->middleware('auth')
    ->name('events.join');

Route::post('/events', [EventController::class, 'store'])
    ->middleware('auth')
    ->name('events.store');

Route::put('/events/{id}', [EventController::class, 'update'])
    ->middleware('auth')
    ->name('events.update');

Route::delete('/events/{id}', [EventController::class, 'destroy'])
    ->middleware('auth')
    ->name('events.destroy');

Route::delete('/events/{id}/leave', [EventController::class, 'leave'])
    ->middleware('auth')
    ->name('events.leave');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::get('/profile', function () {
    return view('profilepage', [
        'events' => Event::where('user_id', Auth::id())->get(),
    ]);
})->middleware('auth')->name('profile');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
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
    ->middleware('auth')
    ->name('logout');
