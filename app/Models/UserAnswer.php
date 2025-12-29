<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    // Menentukan nama tabel karena tidak menggunakan jamak (user_answers)
    protected $table = 'useranswer';

    // Menentukan Primary Key kustom
    protected $primaryKey = 'answer_id';

    // Kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'question_id',
        'user_answers',
        'is_correct',
    ];

    // Casting tipe data agar is_correct selalu terbaca sebagai boolean
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Relasi ke model User
     * Jawaban ini milik seorang User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Question
     * Jawaban ini merujuk pada satu Pertanyaan.
     */
    public function question(): BelongsTo
    {
        // Parameter kedua: FK di tabel useranswer
        // Parameter ketiga: PK di tabel questions
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}