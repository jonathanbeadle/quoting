<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;
use Illuminate\Support\Facades\DB;

class ClearDealsCommand extends Command
{
    protected $signature = 'deals:clear';
    protected $description = 'Clear all deals from the database';

    public function handle()
    {
        try {
            DB::beginTransaction();
            
            // First set foreign key checks to 0
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Truncate the deals table
            Deal::truncate();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            $this->info('All deals have been successfully cleared from the database.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to clear deals: ' . $e->getMessage());
        }
    }
}