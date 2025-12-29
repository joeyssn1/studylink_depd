<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyCounting; // Pastikan Model ini sudah kamu buat
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data counting milik user yang sedang login
        $stats = StudyCounting::where('user_id', Auth::id())->first();

        // Jika data belum ada (user baru), kirim nilai 0
        return view('homepage', [
            'pomodoro_count' => $stats->pomodoro_count ?? 0,
            'active_count'   => $stats->active_count ?? 0,
        ]);
    }
}