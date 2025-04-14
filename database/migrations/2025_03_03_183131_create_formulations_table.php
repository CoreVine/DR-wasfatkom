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
        Schema::create('formulations', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->string('barcode')->nullable();
            $table->string('sku')->nullable();
            $table->string('code')->nullable();
            $table->string('ingredients')->nullable();
            $table->boolean('is_active')->default(1);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('expire_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulations');
    }
};
