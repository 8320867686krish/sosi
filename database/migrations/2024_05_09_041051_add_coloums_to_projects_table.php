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
            $table->string('leb2LabList')->nullable();
            $table->string('leb1LaboratoryResult1')->nullable();
            $table->string('leb1LaboratoryResult2')->nullable();
            $table->string('leb2LaboratoryResult1')->nullable();
            $table->string('leb2LaboratoryResult2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->string('leb2LabList')->nullable();
            $table->string('leb1LaboratoryResult1')->nullable();
            $table->string('leb1LaboratoryResult2')->nullable();
            $table->string('leb2LaboratoryResult1')->nullable();
            $table->string('leb2LaboratoryResult2')->nullable();
        });
    }
};
