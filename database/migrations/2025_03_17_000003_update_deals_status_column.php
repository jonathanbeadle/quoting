<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    public function up()
    {
        // Register the "enum" type if not already registered
        if (!Type::hasType('enum')) {
            Type::addType('enum', \Doctrine\DBAL\Types\StringType::class);
        }

        $connection = Schema::getConnection();
        $platform = $connection->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('deals', function (Blueprint $table) {
            $table->enum('status', [
                'Initial Enquiry',
                'Quote Sent',
                'Quote Accepted',
                'Finance Process',
                'Order Sent',
                'Order Accepted',
                'Order Process',
                'Closed'
            ])->default('Initial Enquiry')->change();
        });
    }

    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->enum('status', ['open', 'closed'])->default('open')->change();
        });
    }
};