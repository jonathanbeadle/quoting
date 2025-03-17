<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, convert the enum to a string to avoid Doctrine DBAL issues
        DB::statement('ALTER TABLE deals MODIFY COLUMN status VARCHAR(255)');

        // Then update the column with the new allowed values
        DB::statement("ALTER TABLE deals MODIFY COLUMN status ENUM(
            'Initial Enquiry',
            'Quote Sent',
            'Quote Accepted',
            'Finance Process',
            'Order Sent',
            'Order Accepted',
            'Order Process',
            'Closed'
        ) NOT NULL DEFAULT 'Initial Enquiry'");

        // Update any existing 'open' statuses to 'Initial Enquiry'
        DB::statement("UPDATE deals SET status = 'Initial Enquiry' WHERE status = 'open'");
        
        // Update any existing 'closed' statuses to 'Closed'
        DB::statement("UPDATE deals SET status = 'Closed' WHERE status = 'closed'");
    }

    public function down()
    {
        // Convert back to the original enum
        DB::statement('ALTER TABLE deals MODIFY COLUMN status VARCHAR(255)');
        DB::statement("ALTER TABLE deals MODIFY COLUMN status ENUM('open', 'closed') NOT NULL DEFAULT 'open'");
        
        // Update the new statuses back to the original ones
        DB::statement("UPDATE deals SET status = 'open' WHERE status != 'Closed'");
        DB::statement("UPDATE deals SET status = 'closed' WHERE status = 'Closed'");
    }
};