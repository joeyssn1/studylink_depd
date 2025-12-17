<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('useranswer', function (Blueprint $table) {
            // answer_id sebagai Primary Key (PK)
            $table->id('answer_id');

            // user_id sebagai Foreign Key (FK) yang terhubung ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // question_id sebagai Foreign Key (FK) yang terhubung ke tabel questions
            // Menggunakan 'question_id' sebagai referensi kolom di tabel asal
            $table->foreignId('question_id')->constrained('questions', 'question_id')->onDelete('cascade');

            // Jawaban dari user dibatasi hanya A, B, C, atau D menggunakan ENUM
            $table->enum('user_answers', ['A', 'B', 'C', 'D']);

            // is_correct sebagai boolean untuk menandai benar (true) atau salah (false)
            $table->boolean('is_correct')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('useranswer');
    }
};

//dicontroller nanti
// Contoh logika di Controller
// $question = Question::find($request->question_id);
// $isCorrect = ($request->user_answer == $question->correct_answer);

// UserAnswer::create([
//     'user_id' => auth()->id(),
//     'question_id' => $request->question_id,
//     'user_answers' => $request->user_answer,
//     'is_correct' => $isCorrect,
// ]);