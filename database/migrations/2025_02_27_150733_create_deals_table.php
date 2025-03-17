<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', [
                'Initial Enquiry',
                'Quote Sent',
                'Quote Accepted',
                'Finance Process',
                'Order Sent',
                'Order Accepted',
                'Order Process',
                'Closed'
            ])->default('Initial Enquiry');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deals');
    }
}