<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// รันสรุปผล Dashboard ตอน 03:00 น. ของทุกวัน (Data Pre-aggregation)
Schedule::command('dashboard:calculate-summary')->dailyAt('03:00');
