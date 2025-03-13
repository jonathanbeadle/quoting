<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('token')->nullable()->after('id');
        });

        // Optionally, populate the token for existing orders
        \DB::table('orders')->whereNull('token')->update([
            'token' => \Illuminate\Support\Str::random(20)
        ]);

        // Make token non-nullable if desired (requires Doctrine DBAL if altering)
        Schema::table('orders', function (Blueprint $table) {
            $table->string('token')->unique()->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
