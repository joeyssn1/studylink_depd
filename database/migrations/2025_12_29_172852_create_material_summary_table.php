<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_summary', function (Blueprint $table) {
            $table->id('summary_id');
            $table->unsignedBigInteger('material_id');
            $table->longText('summary_text');
            $table->string('ai_model')->nullable();
            $table->timestamps();

            $table->foreign('material_id')
                  ->references('material_id')
                  ->on('material')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_summary');
    }
};
