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
        //
        Schema::table('check_has_hazmats', function (Blueprint $table) {
            //
            $table->string('check_type')->nullable()->after('type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('check_has_hazmats', function (Blueprint $table) {
            //
        });
    }
};
