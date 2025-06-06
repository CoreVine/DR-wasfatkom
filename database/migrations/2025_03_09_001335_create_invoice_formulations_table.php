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
        Schema::create('invoice_formulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('formulation_id')->constrained()->cascadeOnDelete();
            $table->decimal('price');
            $table->integer('qty');
            $table->decimal('discount')->default(0);
            $table->decimal('total_befor_discount');
            $table->decimal('total');
            $table->longText('the_use')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_formulations');
    }
};
