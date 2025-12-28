<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StudyController;

use App\Models\Event;

use App\Models\StudyCounting; // Pastikan import ini ada di bagian paling atas file routes/web.php

Route::get('/', function () {

    // 1. Jika user tidak login, kirim data kosong agar tidak error
    if (!Auth::check()) {
        return view('homepage', [
            'joinedEvents' => [],
            'pomodoro_count' => 0,
            'active_count' => 0
        ]);
    }

    // 2. Logic lama: Mengambil Events
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

    // 3. Logic BARU: Mengambil data statistik belajar
    // Kita cari data berdasarkan user_id yang sedang login
    $stats = StudyCounting::where('user_id', Auth::id())->first();

    // 4. Kirim semua data ke view homepage
    return view('homepage', [
        'joinedEvents' => $events,
        'pomodoro_count' => $stats->pomodoro_count ?? 0, // Jika data null, tampilkan 0
        'active_count' => $stats->active_count ?? 0,     // Jika data null, tampilkan 0
    ]);
})->name('home');

// Simpan history awal
Route::post('/study/store', [StudyController::class, 'store'])->name('study.store');

// Halaman utama Pomodoro (mengirim ID study agar tersambung)
Route::get('/pomodoro/{id}', [StudyController::class, 'pomodoroPage'])->name('pomodoro.show');

// Simpan settingan pomodoro
Route::post('/pomodoro/store', [StudyController::class, 'storePomodoro'])->name('pomodoro.store');

// Halaman utama Active Recall
Route::get('/active-recall/{id}', [StudyController::class, 'activeRecallPage'])->name('active-recall.show');

Route::get('/study', fn() => view('studypage'));
Route::get('/pomodoro', fn() => view('pomodoro'));
//  Route::get('/active-recall', fn() => view('recall'));

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
