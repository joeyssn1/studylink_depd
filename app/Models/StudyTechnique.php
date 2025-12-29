<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyTechnique extends Model
{
    // 1. Beritahu Laravel nama tabel yang benar di database
    protected $table = 'studytechnique';

    // 2. Beritahu Laravel Primary Key-nya bukan 'id'
    protected $primaryKey = 'study_id';

    // 3. Daftarkan kolom agar bisa diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'study_type',
        'subject_name',
    ];

    public function pomodoro()
    {
        return $this->hasOne(Pomodoro::class, 'study_id', 'study_id');
    }
}
