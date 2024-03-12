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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('manager_name')->nullable();
            $table->string('manager_email')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('manager_address')->nullable();
            $table->string('rpsl')->nullable();
            $table->string('manager_website')->nullable();
            $table->string('manager_contact_person_name')->nullable();
            $table->string('manager_contact_person_email')->nullable();
            $table->string('manager_contact_person_phone')->nullable();
            $table->string('manager_tax_information')->nullable();
            $table->string('manager_logo')->nullable();
            $table->string('manager_initials')->nullable();
            $table->boolean('isSameAsManager')->default(0);
            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('IMO_ship_owner_details')->nullable();
            $table->string('owner_contact_person_name')->nullable();
            $table->string('owner_contact_person_email')->nullable();
            $table->string('owner_contact_person_phone')->nullable();
            $table->string('owner_website')->nullable();
            $table->string('owner_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
