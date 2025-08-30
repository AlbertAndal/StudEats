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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('calories')->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->string('cuisine_type')->default('Filipino');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index(['cuisine_type', 'difficulty']);
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
