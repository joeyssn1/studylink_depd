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
        Schema::create('pomodoro', function (Blueprint $table) {
            // pomodoro_id sebagai Primary Key (PK)
            $table->id('pomodoro_id');

            // study_id sebagai Foreign Key (FK) yang terhubung ke tabel studytechnique
            // Pastikan tabel 'studytechnique' sudah dibuat sebelum menjalankan migrasi ini
            $table->foreignId('study_id')->constrained('studytechnique', 'study_id')->onDelete('cascade');

            // focus_time dan rest_time menggunakan unsignedTinyInteger (0-255)
            // Batasan 1-59 akan divalidasi di level aplikasi (Controller)
            $table->unsignedTinyInteger('focus_time');
            $table->unsignedTinyInteger('rest_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pomodoro');
    }
};

//dicontroller
//$request->validate([
//    'focus_time' => 'required|integer|between:1,59',
//    'rest_time'  => 'required|integer|between:1,59',
// ]);