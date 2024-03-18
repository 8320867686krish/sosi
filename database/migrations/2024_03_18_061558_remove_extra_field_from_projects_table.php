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
        Schema::table('projects', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn(['manager_details', 'dimensions', 'builder_name', 'additional_hazmats', 'build_date']);

            // Alter columns to make them nullable
            $table->string('building_details')->nullable()->change();
            $table->string('port_of_registry')->nullable()->change();
            $table->string('vessel_class')->nullable()->change();
            $table->string('vessel_previous_name')->nullable()->change();
            $table->string('ihm_class')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Recreate dropped columns
            $table->string('manager_details');
            $table->string('dimensions');
            $table->string('builder_name');
            $table->string('additional_hazmats');
            $table->string('build_date');

            // Revert nullable changes
            $table->string('building_details')->nullable(false)->change();
            $table->string('port_of_registry')->nullable(false)->change();
            $table->string('vessel_class')->nullable(false)->change();
            $table->string('vessel_previous_name')->nullable(false)->change();
            $table->string('ihm_class')->nullable(false)->change();
        });
    }
};

