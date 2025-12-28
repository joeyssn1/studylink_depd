<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyTechnique;
use App\Models\Pomodoro;
use App\Models\StudyCounting;
use Illuminate\Support\Facades\Auth;

class StudyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'study_type' => 'required|in:ActiveRecall,Pomodoro',
        ]);

        $study = StudyTechnique::create([
            'user_id'      => Auth::id(),
            'study_type'   => $request->study_type,
            'subject_name' => $request->subject_name,
        ]);
        // 2. Update atau Buat data di StudyCounting
        // updateOrCreate akan mencari data berdasarkan user_id, jika tidak ada maka dibuat baru
        $counting = StudyCounting::firstOrCreate(
            ['user_id' => Auth::id()],
            ['pomodoro_count' => 0, 'active_count' => 0]
        );

        // 3. Tambahkan count berdasarkan tipenya
        if ($request->study_type === 'Pomodoro') {
            $counting->increment('pomodoro_count');
            return redirect()->route('pomodoro.show', $study->study_id);
        } else {
            $counting->increment('active_count');
            return redirect()->route('active-recall.show', $study->study_id);
        }

        // Redirect berdasarkan tipe studi
        if ($request->study_type === 'ActiveRecall') {
            return redirect()->route('active-recall.show', $study->study_id);
        }

        return redirect()->route('pomodoro.show', $study->study_id);
    }

    // Tambahkan method untuk menampilkan halaman Active Recall
    public function activeRecallPage($id)
    {
        $study = StudyTechnique::findOrFail($id);
        return view('recall', compact('study'));
    }

    public function pomodoroPage($id)
    {
        // Ambil data study untuk memastikan datanya ada
        $study = StudyTechnique::findOrFail($id);

        return view('pomodoro', compact('study'));
    }

    public function storePomodoro(Request $request)
    {
        $request->validate([
            'study_id' => 'required|exists:studytechnique,study_id',
            'focus_time' => 'required|integer|min:1|max:60',
            'rest_time' => 'required|integer|min:1|max:60',
        ]);

        // Proses simpan
        Pomodoro::create([
            'study_id' => $request->study_id,
            'focus_time' => $request->focus_time,
            'rest_time' => $request->rest_time,
        ]);

        return response()->json(['status' => 'success']);
    }
}
