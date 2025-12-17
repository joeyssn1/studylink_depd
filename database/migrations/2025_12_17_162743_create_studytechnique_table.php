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
        Schema::create('studytechnique', function (Blueprint $table) {
            // study_id sebagai Primary Key (PK)
            $table->id('study_id');

            // user_id sebagai Foreign Key (FK) yang terhubung ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // study_type menggunakan ENUM untuk membatasi pilihan tipe studi
            $table->enum('study_type', ['ActiveRecall', 'Pomodoro']);

            // subject_name untuk menyimpan nama mata pelajaran atau topik
            $table->string('subject_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studytechnique');
    }
};

