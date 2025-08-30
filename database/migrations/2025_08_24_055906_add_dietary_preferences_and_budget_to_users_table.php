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
        Schema::table('users', function (Blueprint $table) {
            $table->json('dietary_preferences')->nullable()->after('email');
            $table->decimal('daily_budget', 8, 2)->nullable()->after('dietary_preferences');
            $table->string('age')->nullable()->after('daily_budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dietary_preferences', 'daily_budget', 'age']);
        });
    }
};
