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
            $table->string('gender')->nullable()->after('age');
            $table->string('activity_level')->nullable()->after('gender');
            $table->decimal('height', 5, 1)->nullable()->after('activity_level');
            $table->string('height_unit', 2)->default('cm')->after('height');
            $table->decimal('weight', 5, 1)->nullable()->after('height_unit');
            $table->string('weight_unit', 3)->default('kg')->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'activity_level', 'height', 'height_unit', 'weight', 'weight_unit']);
        });
    }
};
