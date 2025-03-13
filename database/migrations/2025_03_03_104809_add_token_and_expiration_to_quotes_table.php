<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add columns only if they don't exist yet
        Schema::table('quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('quotes', 'token')) {
                $table->string('token')->nullable()->after('id');
            }
            if (!Schema::hasColumn('quotes', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('token');
            }
            if (!Schema::hasColumn('quotes', 'status')) {
                $table->enum('status', ['active', 'expired'])->default('active')->after('expires_at');
            }
        });

        // Populate the token for existing records where token is null or empty
        $quotes = DB::table('quotes')
            ->whereNull('token')
            ->orWhere('token', '')
            ->get();

        foreach ($quotes as $quote) {
            DB::table('quotes')
                ->where('id', $quote->id)
                ->update(['token' => Str::random(20)]);
        }

        // Alter the token column to be non-nullable and unique
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('token')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['token', 'expires_at', 'status']);
        });
    }
};
