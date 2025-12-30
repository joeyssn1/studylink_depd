<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialSummary extends Model
{
    protected $table = 'material_summary';
    protected $primaryKey = 'summary_id';

    protected $fillable = [
        'material_id',
        'summary_text',
        'ai_model',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
