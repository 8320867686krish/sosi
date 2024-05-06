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
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->string('laboratorie1')->nullable()->after('deck_image');
            $table->string('laboratorie2')->nullable()->after('laboratorie1');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
