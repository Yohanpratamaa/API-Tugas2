<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            try {
                Log::info('Running createItemStatusNotification...');
                app(\App\Http\Controllers\Api\NotificationController::class)->createItemStatusNotification();
                Log::info('Running checkStockAndExpiration...');
                app(\App\Http\Controllers\Api\NotificationController::class)->checkStockAndExpiration();
                Log::info('Scheduled tasks completed successfully.');
            } catch (\Exception $e) {
                Log::error('Error running scheduled tasks: ' . $e->getMessage());
            }
        })->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}