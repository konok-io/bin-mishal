<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Tasks
Schedule::command('invoices:check-overdue')->dailyAt('08:00');
Schedule::command('appointments:send-reminders')->dailyAt('07:00');
Schedule::command('documents:check-expiry --days=90')->dailyAt('06:00');
Schedule::command('documents:check-expiry --days=60')->dailyAt('06:00');
Schedule::command('documents:check-expiry --days=30')->dailyAt('06:00');
Schedule::command('documents:check-expiry --days=7')->dailyAt('06:00');
Schedule::command('reports:send-summary')->weeklyOn(0, '09:00'); // Sunday at 9 AM
