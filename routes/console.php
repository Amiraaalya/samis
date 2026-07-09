<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('samis:update-terlambat')->dailyAt('00:05');
Schedule::command('samis:send-reminder')->dailyAt('07:00');