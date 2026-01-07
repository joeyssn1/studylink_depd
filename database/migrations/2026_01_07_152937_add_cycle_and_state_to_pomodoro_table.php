<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pomodoro', function (Blueprint $table) {
            $table->unsignedTinyInteger('total_cycles')->default(1);
            $table->unsignedTinyInteger('current_cycle')->default(1);

            $table->boolean('is_focus')->default(true);
            $table->integer('remaining_seconds')->nullable();

            $table->enum('status', ['in_progress', 'paused', 'completed'])
                  ->default('in_progress');

            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pomodoro', function (Blueprint $table) {
            $table->dropColumn([
                'total_cycles',
                'current_cycle',
                'is_focus',
                'remaining_seconds',
                'status',
                'completed_at',
            ]);
        });
    }
};
