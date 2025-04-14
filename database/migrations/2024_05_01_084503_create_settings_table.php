<?php

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/



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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->longText('value')->nullable();
            $table->string('group');
            $table->string('page');
            $table->string('type');
            $table->string("validation");
            $table->string('config_name')->nullable();
            $table->string('list_values')->nullable()->comment('فى حالة لو كان checkbox , radio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
