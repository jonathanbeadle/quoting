<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First check if the column exists, if not, add it
        if (!Schema::hasColumn('orders', 'sent')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('sent')->default(false)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to drop the column, as it should be normally part of the orders table
    }
};