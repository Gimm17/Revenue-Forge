<?php

use App\Jobs\AggregateDailyMetricsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Aggregate daily metrics every day at 1 AM
Schedule::job(new AggregateDailyMetricsJob)->dailyAt('01:00');
