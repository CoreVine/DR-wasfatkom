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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('clinic_name')->nullable();
            $table->string('type')->comment('App\Enums\UserRoleEnum');
            $table->string('mobile_country_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_block')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->date('date_of_birth')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password');
            $table->string('lang')->default('ar');
            $table->string("role")->nullable()->comment("Enums => App\Enums\UserRoleEnum");
            $table->boolean('is_all_doctor')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
