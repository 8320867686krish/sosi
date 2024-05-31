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
        Schema::table('lab_results', function (Blueprint $table) {
            $table->string('sample_weight')->nullable()->after('lab_remarks');
            $table->string('sample_area')->nullable()->after('sample_weight');
            $table->string('density')->nullable()->after('sample_area');
            $table->string('affected_area')->nullable()->after('density');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_results', function (Blueprint $table) {
            $table->dropColumn('sample_weight');
            $table->dropColumn('sample_area');
            $table->dropColumn('density');
            $table->dropColumn('affected_area');
        });
    }
};
