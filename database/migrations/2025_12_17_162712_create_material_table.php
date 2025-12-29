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
        Schema::create('material', function (Blueprint $table) {
            // material_id sebagai Primary Key (PK)
            $table->id('material_id');

            // user_id sebagai Foreign Key (FK) yang terhubung ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Kolom untuk informasi file
            $table->string('file_name'); // Menyimpan nama asli file
            $table->string('file_path'); // Menyimpan lokasi penyimpanan file di storage
            $table->string('file_type')->default('pdf'); // Menyimpan tipe file (pdf)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material');
    }
};
//dicontroller nanti
//$request->validate([
//    'file_material' => 'required|mimes:pdf|max:15360', // 15360 KB = 15 MB
// ]);