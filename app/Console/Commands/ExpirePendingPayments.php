<?php

namespace App\Console\Commands;

use App\Jobs\ExpirePendingPayments as ExpirePendingPaymentsJob;
use Illuminate\Console\Command;

class ExpirePendingPayments extends Command
{
    protected $signature = 'payment:expire';

    protected $description = 'Expire payment requests pending for more than 48 hours';

    public function handle(): void
    {
        ExpirePendingPaymentsJob::dispatch();

        $this->info('Pending payments expiration job dispatched.');
    }
}
