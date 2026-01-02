<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StudyController;

use App\Models\Event;
use App\Models\StudyCounting; 

// ====================================================
// HALAMAN UTAMA (HOMEPAGE)
// ====================================================
Route::get('/', function () {

    // 1. Jika user tidak login, kirim data kosong agar tidak error
    if (!Auth::check()) {
        return view('homepage', [
            'joinedEvents' => [],
            'pomodoro_count' => 0,
            'active_count' => 0
        ]);
    }

    $user = Auth::user();

    // 2. LOGIC BARU (REVISI): Mengambil Event Buatan Sendiri + Event Join
    
    // A. Ambil event yang dibuat sendiri
    $createdEvents = Event::where('user_id', $user->id)->get();

    // B. Ambil event yang di-join (sebagai peserta)
    $joinedEvents = $user->joinedEvents ?? collect(); 

    // C. Gabungkan keduanya & hapus duplikat jika ada
    $allEvents = $createdEvents->merge($joinedEvents)->unique('id');

    // 3. Format data agar bisa dibaca oleh Javascript Kalender
    // Kita group berdasarkan tanggal ('date')
    $formattedEvents = $allEvents->groupBy('date')->map(function ($items) {
        return $items->map(function ($event) {
            return [
                'id' => $event->id,             // Penting untuk Edit/Delete
                'user_id' => $event->user_id,   // Penting untuk validasi pemilik
                'event_name' => $event->event_name,
                'time' => $event->time,
                'description' => $event->description,
            ];
        });
    });

    // 4. Mengambil data statistik belajar (Pomodoro & Active Recall)
    $stats = StudyCounting::where('user_id', $user->id)->first();

    // 5. Kirim semua data ke view homepage
    return view('homepage', [
        'joinedEvents' => $formattedEvents,       // <-- Data Event sekarang sudah LENGKAP
        'pomodoro_count' => $stats->pomodoro_count ?? 0,
        'active_count' => $stats->active_count ?? 0,
    ]);
})->name('home');


// ====================================================
// FITUR STUDY & POMODORO
// ====================================================

// Simpan history awal
Route::post('/study/store', [StudyController::class, 'store'])->name('study.store');

// Halaman utama Pomodoro (mengirim ID study agar tersambung)
Route::get('/pomodoro/{id}', [StudyController::class, 'pomodoroPage'])->name('pomodoro.show');

// Simpan settingan pomodoro
Route::post('/pomodoro/store', [StudyController::class, 'storePomodoro'])->name('pomodoro.store');

// Halaman utama Active Recall
Route::get('/active-recall/{id}', [StudyController::class, 'activeRecallPage'])->name('active-recall.show');

// Upload file material
Route::post('/material/upload', [StudyController::class, 'uploadMaterial'])->name('material.upload');

// Generate question & submit answer
Route::post('/active-recall/generate', [StudyController::class, 'generateQuestions'])->name('recall.generate');
Route::post('/active-recall/submit', [StudyController::class, 'submitAnswer'])->name('recall.submit');

Route::get('/study', fn() => view('studypage'));
Route::get('/pomodoro', fn() => view('pomodoro'));

// Halaman History Belajar
Route::get('/history', [StudyController::class, 'history'])->name('study.history')->middleware('auth');


// ====================================================
// FITUR EVENTS & PROFILE
// ====================================================

// Join Event via Code
Route::post('/join-event', [EventController::class, 'joinByCode'])
    ->middleware('auth')
    ->name('events.join');

// Halaman Profile
Route::get('/profile', function () {
    return view('profilepage', [
        'events' => Event::where('user_id', Auth::id())->get()
    ]);
})->middleware('auth')->name('profile');

// Create New Event
Route::post('/events', [EventController::class, 'store'])
    ->middleware('auth');

// Update Event (Edit)
Route::put('/events/{id}', [EventController::class, 'update'])
    ->middleware('auth')
    ->name('events.update');

// Delete Event (Hapus)
Route::delete('/events/{id}', [EventController::class, 'destroy'])
    ->middleware('auth')
    ->name('events.destroy');


// ====================================================
// AUTHENTICATION
// ====================================================

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