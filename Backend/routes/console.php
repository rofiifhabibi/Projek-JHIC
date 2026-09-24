<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Schedule::call(function () {
    DB::table('requests')
        ->where('status', 'ACTIVE')
        ->whereNotNull('expiry_time')
        ->where('expiry_time', '<', Carbon::now())
        ->update(['status' => 'OVERDUE']);
})->everyMinute();