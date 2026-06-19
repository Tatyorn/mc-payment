<?php

use App\Console\Commands\ExpirePendingPayments;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ExpirePendingPayments::class)->hourly();
