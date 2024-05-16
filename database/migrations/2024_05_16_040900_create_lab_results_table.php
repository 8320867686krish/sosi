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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('check_id')->nullable();
            $table->foreign('check_id')->references('id')->on('checks')->onDelete('cascade');
            $table->unsignedBigInteger('hazmat_id')->nullable();
            $table->foreign('hazmat_id')->references('id')->on('hazmats')->onDelete('cascade');
            $table->string('IHM_part', 20)->nullable();
            $table->string('unit', 10)->nullable();
            $table->integer('number')->nullable();
            $table->decimal('total', 8,2)->nullable();
            $table->text('lab_remarks')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
