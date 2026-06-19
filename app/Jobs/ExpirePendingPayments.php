<?php

namespace App\Jobs;

use App\Enums\PaymentRequestStatus;
use App\Models\PaymentRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class ExpirePendingPayments implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(): void
    {
        PaymentRequest::query()
            ->where('status', PaymentRequestStatus::PENDING)
            ->where('created_at', '<', now()->subHours(48))
            ->update(['status' => PaymentRequestStatus::EXPIRED]);
    }
}
