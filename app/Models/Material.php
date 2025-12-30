<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';
    protected $primaryKey = 'material_id';
    protected $fillable = ['user_id', 'file_name', 'file_path', 'file_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function summary()
    {
    return $this->hasOne(MaterialSummary::class, 'material_id');
    }

}
