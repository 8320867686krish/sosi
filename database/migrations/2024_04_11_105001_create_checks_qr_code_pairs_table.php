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
        Schema::create('checks_qr_code_pairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('check_id')->nullable();
            $table->foreign('check_id')->references('id')->on('checks')->onDelete('cascade');
            $table->string('pairWitthTag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checks_qr_code_pairs');
    }
};
