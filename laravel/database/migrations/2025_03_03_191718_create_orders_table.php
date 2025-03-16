<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Reference to the original quote
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('set null');

            // References for customer and vehicle
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');

            // Finance details (copy these from your quote)
            $table->string('finance_type');
            $table->integer('contract_length');
            $table->integer('annual_mileage');
            $table->string('payment_profile');
            $table->decimal('deposit', 8, 2);
            $table->decimal('monthly_payment', 8, 2);
            $table->boolean('maintenance')->default(false);
            $table->decimal('document_fee', 8, 2);

            // Order status: initially set to 'pending'
            // You can allow values like 'pending', 'approved', 'cancelled', etc.
            $table->enum('status', ['pending', 'active', 'expired', 'confirmed'])->default('pending');
            $table->string('token')->unique();
            $table->boolean('sent')->default(false);
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
