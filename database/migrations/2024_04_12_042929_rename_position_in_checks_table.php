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
        Schema::table('checks', function (Blueprint $table) {
            // Rename 'position' column to 'location' if it exists
            if (Schema::hasColumn('checks', 'position')) {
                $table->renameColumn('position', 'location');
            }

            // Rename 'sub_position' column to 'sub_location' if it exists
            if (Schema::hasColumn('checks', 'sub_position')) {
                $table->renameColumn('sub_position', 'sub_location');
            }


            // Drop 'description' column if it exists
            if (Schema::hasColumn('checks', 'description')) {
                $table->dropColumn('description');
            }

            // Drop 'compartment' column if it exists
            if (Schema::hasColumn('checks', 'compartment')) {
                $table->dropColumn('compartment');
            }

            // Drop 'material' column if it exists
            if (Schema::hasColumn('checks', 'material')) {
                $table->dropColumn('material');
            }

            // Drop 'color' column if it exists
            if (Schema::hasColumn('checks', 'color')) {
                $table->dropColumn('color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checks', function (Blueprint $table) {
            if (Schema::hasColumn('checks', 'location')) {
                $table->renameColumn('location', 'position');
            }

            if (Schema::hasColumn('checks', 'sub_location')) {
                $table->renameColumn('sub_location', 'sub_position');
            }

            $table->string('description')->nullable();
            $table->string('compartment')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
        });
    }
};
