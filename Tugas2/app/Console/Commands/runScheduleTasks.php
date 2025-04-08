<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunScheduleTasks extends Command
{
    protected $signature = 'schedule:run';
    protected $description = 'Manually run scheduled tasks';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting scheduled tasks manually...');
        Log::info('Running scheduled tasks manually...');

        try {
            Log::info('Running createItemStatusNotification...');
            app(\App\Http\Controllers\Api\NotificationController::class)->createItemStatusNotification();
            Log::info('Running checkStockAndExpiration...');
            app(\App\Http\Controllers\Api\NotificationController::class)->checkStockAndExpiration();
            Log::info('Scheduled tasks completed successfully.');
        } catch (\Exception $e) {
            Log::error('Error running scheduled tasks: ' . $e->getMessage());
        }
    }
}