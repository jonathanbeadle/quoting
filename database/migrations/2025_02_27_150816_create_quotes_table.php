<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            // New Quote details fields:
            $table->enum('finance_type', ['Hire Purchase', 'Finance Lease', 'Operating Lease', 'Business Contract Hire']);
            $table->integer('contract_length'); // e.g., 24, 36, 48, or 60 months
            $table->integer('annual_mileage');  // Annual Mileage
            $table->string('payment_profile');  // Payment Profile
            $table->decimal('deposit', 10, 2);    // Deposit
            $table->decimal('monthly_payment', 10, 2); // Monthly Payment
            $table->boolean('maintenance');     // Maintenance (Yes/No)
            $table->decimal('document_fee', 10, 2); // Document Fee
            $table->boolean('sent')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
