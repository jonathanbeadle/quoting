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
        if (Schema::hasTable('order_trackings') && !Schema::hasColumn('order_trackings', 'expires_at')) {
            Schema::table('order_trackings', function (Blueprint $table) {
                $table->timestamp('expires_at')->nullable()->after('user_id');
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
        if (Schema::hasTable('order_trackings') && Schema::hasColumn('order_trackings', 'expires_at')) {
            Schema::table('order_trackings', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        }
    }
};
