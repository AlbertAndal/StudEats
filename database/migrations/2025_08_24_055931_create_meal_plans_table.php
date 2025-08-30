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
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'scheduled_date', 'meal_type'], 'unique_user_date_meal_type');
            $table->index(['user_id', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};
