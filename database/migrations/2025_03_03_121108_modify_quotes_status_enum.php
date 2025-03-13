<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

class ModifyQuotesStatusEnum extends Migration
{
    public function up()
    {
        // Register the "enum" type if not already registered
        if (!Type::hasType('enum')) {
            Type::addType('enum', \Doctrine\DBAL\Types\StringType::class);
        }

        $connection = Schema::getConnection();
        $platform = $connection->getDoctrineSchemaManager()->getDatabasePlatform();
        // Tell Doctrine to treat the 'enum' column as a 'string'
        $platform->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('quotes', function (Blueprint $table) {
            // Update the status column to allow 'confirmed'
            $table->enum('status', ['active', 'expired', 'confirmed'])
                  ->default('active')
                  ->change();
        });
    }

    public function down()
    {
        if (!Type::hasType('enum')) {
            Type::addType('enum', \Doctrine\DBAL\Types\StringType::class);
        }

        $connection = Schema::getConnection();
        $platform = $connection->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('quotes', function (Blueprint $table) {
            $table->enum('status', ['active', 'expired'])
                  ->default('active')
                  ->change();
        });
    }
}
