<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddSentColumnToOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:add-sent-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the sent column directly to the orders table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Check if the column exists first
            $columnExists = DB::select("SHOW COLUMNS FROM `orders` LIKE 'sent'");
            
            if (empty($columnExists)) {
                DB::statement('ALTER TABLE `orders` ADD COLUMN `sent` BOOLEAN DEFAULT FALSE AFTER `token`');
                $this->info('The "sent" column was successfully added to the orders table!');
            } else {
                $this->info('The "sent" column already exists in the orders table.');
            }
        } catch (\Exception $e) {
            $this->error('Failed to add the column: ' . $e->getMessage());
        }
    }
}