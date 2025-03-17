<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First convert status to varchar to avoid issues with changing enum
        DB::statement('ALTER TABLE deals MODIFY COLUMN status VARCHAR(255)');

        // Then update to new enum with all statuses
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

        // Update existing records
        DB::statement("UPDATE deals SET status = 'Initial Enquiry' WHERE status = 'open'");
        DB::statement("UPDATE deals SET status = 'Closed' WHERE status = 'closed'");
    }

    public function down()
    {
        // Convert back to varchar first
        DB::statement('ALTER TABLE deals MODIFY COLUMN status VARCHAR(255)');
        
        // Then revert to original enum
        DB::statement("ALTER TABLE deals MODIFY COLUMN status ENUM('open', 'closed') NOT NULL DEFAULT 'open'");
        
        // Update the statuses back
        DB::statement("UPDATE deals SET status = 'open' WHERE status != 'Closed'");
        DB::statement("UPDATE deals SET status = 'closed' WHERE status = 'Closed'");
    }
};