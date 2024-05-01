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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('ship_name')->nullable();
            $table->string('ship_type')->nullable();
            $table->string('ihm_table', 50)->nullable();
            $table->string('project_no')->nullable();
            $table->string('imo_number')->nullable()->unique();
            $table->string('call_sign')->nullable();
            $table->string('manager_details')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('builder_name')->nullable();
            $table->string('building_details')->default('');
            $table->string('build_date')->default('');
            $table->string('port_of_registry')->default('');
            $table->string('vessel_class')->default('');
            $table->string('gross_tonnage')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('flag_of_ship')->nullable();
            $table->string('x_breadth_depth')->nullable();
            $table->string('ga_plan')->nullable();
            $table->string('image')->nullable();
            $table->string('vessel_previous_name')->default('');
            $table->string('ihm_class')->default('');
            $table->string('additional_hazmats')->default('');
            $table->string('survey_location_name')->nullable();
            $table->string('survey_location_address')->nullable();
            $table->string('survey_type')->nullable();
            $table->date('survey_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
