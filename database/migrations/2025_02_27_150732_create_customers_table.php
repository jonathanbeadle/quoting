<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // Required customer details
            $table->string('name');           // Customer Name
            $table->string('business_name');  // Business Name
            $table->string('email')->unique(); // Email Address
            $table->string('phone');          // Phone Number
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
