<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    // Nama tabel jika tidak mengikuti konvensi jamak (questions)
    protected $table = 'questions';

    // Mendefinisikan Primary Key karena bukan 'id'
    protected $primaryKey = 'question_id';

    // Kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'study_id',
        'question_detail',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
    ];

    /**
     * Relasi ke model StudyTechnique (Many-to-One)
     * Satu pertanyaan dimiliki oleh satu teknik studi.
     */
    public function studyTechnique(): BelongsTo
    {
        // Pastikan nama model target adalah StudyTechnique
        // Parameter kedua adalah Foreign Key di tabel questions
        // Parameter ketiga adalah Local Key di tabel studytechnique
        return $this->belongsTo(StudyTechnique::class, 'study_id', 'study_id');
    }
}