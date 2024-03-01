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
            $table->unsignedBigInteger('ship_owners_id')->nullable();
            $table->foreign('ship_owners_id')
                ->references('id')
                ->on('ship_owners')
                ->onDelete('set null');
            $table->string('ship_name')->nullable();
            $table->string('ship_type')->nullable();
            $table->string('ihm_table', 50)->nullable();
            $table->string('project_no')->nullable();
            $table->string('imo_number')->nullable()->unique();
            $table->string('client_name')->default('');
            $table->string('client_details')->default('');
            $table->string('builder_name')->default('');
            $table->string('builder_details')->default('');
            $table->string('build_date')->default('');
            $table->string('delivery_date')->default('');
            $table->string('flag')->default('');
            $table->string('vessal_class')->default('');
            $table->string('ihm_class')->default('');
            $table->string('additional_hazmats')->default('');
            $table->string('survey_location_name')->default('');
            $table->string('survey_location_address')->default('');
            $table->string('survey_type')->default('');
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
