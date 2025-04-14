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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_num')->unique();
            $table->foreignId('doctor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('review_id')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->string('status')->comment('App\Enums\OrderStatusEnum');
            $table->string('client_name');
            $table->string('client_location')->nullable();
            $table->string('client_mobile');
            $table->foreignId('coupon_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('coupon_value')->default(0);
            $table->decimal('sub_total');
            $table->decimal('discount');
            $table->decimal('total');
            $table->decimal('tax')->default(0);
            $table->decimal('tax_value')->default(0);
            $table->longText('notes')->nullable();
            $table->decimal('doctor_commission');
            $table->decimal('doctor_commission_value')->default(0);
            $table->string('payment_order_id')->nullable();
            $table->string('payment_type')->default('Un paid');
            $table->string('qr_code')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
