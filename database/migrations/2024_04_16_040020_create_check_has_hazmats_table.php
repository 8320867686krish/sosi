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
        Schema::create('check_has_hazmats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('check_id')->nullable();
            $table->foreign('check_id')->references('id')->on('checks')->onDelete('cascade');
            $table->unsignedBigInteger('hazmat_id')->nullable();
            $table->foreign('hazmat_id')->references('id')->on('hazmats')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->enum('type', ['Unknown', 'PCHM', 'Not Contained', 'Contained'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_has_hazmats');
    }
};
