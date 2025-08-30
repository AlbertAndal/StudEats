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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
            $table->json('ingredients');
            $table->text('instructions');
            $table->integer('prep_time')->comment('Preparation time in minutes');
            $table->integer('cook_time')->comment('Cooking time in minutes');
            $table->integer('servings')->default(1);
            $table->json('local_alternatives')->nullable()->comment('Alternative ingredients available locally');
            $table->timestamps();
            
            $table->index('meal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
