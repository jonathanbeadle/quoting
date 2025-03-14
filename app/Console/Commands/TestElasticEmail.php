<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestElasticEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {recipient}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Elastic Email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        $this->info("Attempting to send test email to {$recipient}...");
        
        try {
            Mail::raw('This is a test email from your Laravel application using Elastic Email API.', function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('Test Email from Laravel');
            });
            
            $this->info('Email sent successfully!');
            
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
        }
    }
}