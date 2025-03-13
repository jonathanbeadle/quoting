<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            // Required vehicle details
            $table->string('make');              // Make
            $table->string('model');             // Model
            $table->text('specification');       // Specification
            $table->enum('transmission', ['Manual', 'Automatic']);  // Transmission
            $table->enum('fuel_type', ['Diesel', 'Petrol', 'Hybrid', 'Electric']); // Fuel Type
            $table->enum('registration_status', ['New', 'Pre-Registered', 'Used']); // Registration Status
            $table->date('registration_date')->nullable(); // Registration Date (if Pre-Registered)
            $table->text('additional_options')->nullable();  // Additional Options
            $table->text('dealer_fit_options')->nullable();  // Dealer Fit Options
            $table->string('colour');             // Colour
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
