<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:run', function () {
    app(\App\Http\Controllers\Api\NotificationController::class)->createItemStatusNotification();
    app(\App\Http\Controllers\Api\NotificationController::class)->checkStockAndExpiration();
    $this->info('Notification tasks executed!');
})->describe('Run notification tasks');
