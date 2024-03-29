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
            if (Schema::hasColumn('projects', 'manager_details')) {
                $table->dropColumn('manager_details');
            }
            if (Schema::hasColumn('projects', 'dimensions')) {
                $table->dropColumn('dimensions');
            }
            if (Schema::hasColumn('projects', 'builder_name')) {
                $table->dropColumn('builder_name');
            }
            if (Schema::hasColumn('projects', 'additional_hazmats')) {
                $table->dropColumn('additional_hazmats');
            }
            if (Schema::hasColumn('projects', 'build_date')) {
                $table->dropColumn('build_date');
            }

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
            // Recreate dropped columns with appropriate types and default values if needed
            $table->string('manager_details')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('builder_name')->nullable();
            $table->string('additional_hazmats')->nullable();
            $table->string('build_date')->nullable();

            // Revert nullable changes
            $table->string('building_details')->nullable(false)->change();
            $table->string('port_of_registry')->nullable(false)->change();
            $table->string('vessel_class')->nullable(false)->change();
            $table->string('vessel_previous_name')->nullable(false)->change();
            $table->string('ihm_class')->nullable(false)->change();
        });
    }
};
