<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Event extends Model
{
    protected $table = 'event_table';

    protected $fillable = [
        'user_id',
        'event_name',
        'date',
        'start_time',
        'end_time',
        'description',
        'code',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }
}
