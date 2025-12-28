<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyCounting extends Model
{
    // Sesuaikan dengan migration: 'studycounting'
    protected $table = 'studycounting'; 

    // Sesuaikan dengan migration: 'study_id'
    protected $primaryKey = 'study_id';

    protected $fillable = [
        'user_id',
        'pomodoro_count',
        'active_count',
    ];
}