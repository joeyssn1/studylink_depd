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
        Schema::create('studycounting', function (Blueprint $table) {
           // Menggunakan study_id sebagai Primary Key (PK)
            $table->id('study_id'); 
            
            // Membuat user_id sebagai Foreign Key (FK) yang terhubung ke tabel users
            // constrained() secara otomatis akan mencari tabel 'users' dan kolom 'id'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom count dengan nilai default 0
            $table->integer('pomodoro_count')->default(0);
            $table->integer('active_count')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studycounting');
    }
};
