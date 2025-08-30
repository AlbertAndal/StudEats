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
        Schema::create('nutritional_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
            $table->decimal('calories', 8, 2)->default(0);
            $table->decimal('protein', 8, 2)->default(0)->comment('grams');
            $table->decimal('carbs', 8, 2)->default(0)->comment('grams');
            $table->decimal('fats', 8, 2)->default(0)->comment('grams');
            $table->decimal('fiber', 8, 2)->default(0)->comment('grams');
            $table->decimal('sugar', 8, 2)->default(0)->comment('grams');
            $table->decimal('sodium', 8, 2)->default(0)->comment('milligrams');
            $table->timestamps();
            
            $table->unique('meal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutritional_info');
    }
};
