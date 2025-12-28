<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pomodoro extends Model
{
    use HasFactory;

    // 1. Beritahu Laravel nama tabelnya (cek apakah 'pomodoros' atau 'pomodoro')
    protected $table = 'pomodoro';

    // 2. Beritahu Primary Key-nya (karena tadi kamu set pomodoro_id di migration)
    protected $primaryKey = 'pomodoro_id';

    // 3. WAJIB: Daftarkan kolom agar bisa diisi (Penyebab Error 500)
    protected $fillable = [
        'study_id',
        'focus_time',
        'rest_time',
    ];
}
