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
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_completed');
            $table->unsignedTinyInteger('servings')->default(1)->after('notes');
            $table->enum('prep_reminder', ['none', '30min', '1hour', '2hours', '1day'])->default('none')->after('servings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->dropColumn(['notes', 'servings', 'prep_reminder']);
        });
    }
};
