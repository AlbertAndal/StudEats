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
        Schema::create('pdri_references', function (Blueprint $table) {
            $table->id();
            $table->string('gender'); // male, female
            $table->integer('age_min');
            $table->integer('age_max');
            $table->string('activity_level'); // sedentary, low_active, active, very_active
            $table->integer('energy_kcal'); // Daily energy requirement
            $table->decimal('protein_g', 8, 2); // Protein in grams
            $table->decimal('carbohydrates_g', 8, 2); // Carbs in grams
            $table->decimal('total_fat_g', 8, 2); // Total fat in grams
            $table->decimal('fiber_g', 8, 2); // Fiber in grams
            $table->decimal('sodium_mg', 8, 2)->nullable(); // Sodium in mg
            $table->decimal('sugar_g', 8, 2)->nullable(); // Added sugar limit in grams
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdri_references');
    }
};
