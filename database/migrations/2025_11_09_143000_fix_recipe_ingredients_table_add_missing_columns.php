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
        // Check if columns exist before adding them
        if (Schema::hasTable('recipe_ingredients')) {
            Schema::table('recipe_ingredients', function (Blueprint $table) {
                if (!Schema::hasColumn('recipe_ingredients', 'recipe_id')) {
                    $table->foreignId('recipe_id')->after('id')->constrained('recipes')->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('recipe_ingredients', 'ingredient_id')) {
                    $table->foreignId('ingredient_id')->after('recipe_id')->constrained('ingredients')->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('recipe_ingredients', 'quantity')) {
                    $table->decimal('quantity', 10, 2)->default(0)->after('ingredient_id');
                }
                
                if (!Schema::hasColumn('recipe_ingredients', 'unit')) {
                    $table->string('unit', 50)->default('g')->after('quantity');
                }
                
                if (!Schema::hasColumn('recipe_ingredients', 'estimated_cost')) {
                    $table->decimal('estimated_cost', 10, 2)->nullable()->after('unit');
                }
                
                if (!Schema::hasColumn('recipe_ingredients', 'notes')) {
                    $table->text('notes')->nullable()->after('estimated_cost');
                }
            });
            
            // Add unique constraint if it doesn't exist
            try {
                Schema::table('recipe_ingredients', function (Blueprint $table) {
                    $table->unique(['recipe_id', 'ingredient_id'], 'recipe_ingredient_unique');
                });
            } catch (\Exception $e) {
                // Index might already exist, ignore
                \Log::info('Unique index already exists: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('recipe_ingredients')) {
            Schema::table('recipe_ingredients', function (Blueprint $table) {
                // Drop unique constraint first
                try {
                    $table->dropUnique('recipe_ingredient_unique');
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
                
                // Drop foreign keys first
                if (Schema::hasColumn('recipe_ingredients', 'recipe_id')) {
                    $table->dropForeign(['recipe_id']);
                }
                if (Schema::hasColumn('recipe_ingredients', 'ingredient_id')) {
                    $table->dropForeign(['ingredient_id']);
                }
                
                // Drop columns
                $columns = ['recipe_id', 'ingredient_id', 'quantity', 'unit', 'estimated_cost', 'notes'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('recipe_ingredients', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
