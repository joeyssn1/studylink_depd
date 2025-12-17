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
        Schema::create('questions', function (Blueprint $table) {
            // question_id sebagai Primary Key (PK)
            $table->id('question_id');

            // study_id sebagai Foreign Key (FK) yang terhubung ke tabel studytechnique
            // Menggunakan 'study_id' sebagai referensi kolom di tabel asal
            $table->foreignId('study_id')->constrained('studytechnique', 'study_id')->onDelete('cascade');

            // Detail atau isi pertanyaan
            $table->text('question_detail');

            // Pilihan jawaban
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');

            // Jawaban benar dibatasi hanya A, B, C, atau D menggunakan ENUM
            $table->enum('correct_answer', ['A', 'B', 'C', 'D']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
