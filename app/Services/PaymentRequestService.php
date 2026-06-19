<?php

namespace App\Services;

use App\Enums\PaymentRequestStatus;
use App\Exceptions\Errors\BusinessRuleViolation;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class PaymentRequestService
{
    public function __construct(
        private readonly ExchangeRateService $exchangeRateService,
    ) {}

    public function create(array $data, User $user): PaymentRequest
    {
        $currency = strtoupper($data['currency']);

        $exchangeData = $this->exchangeRateService->getRate($currency);

        $amount = $data['amount'];

        $eurAmount = round($amount * $exchangeData['rate'], 2);

        $invoicePath = null;
        if (isset($data['invoice']) && $data['invoice'] instanceof UploadedFile) {
            $invoicePath = $data['invoice']->store('invoices', 'public');
        }

        $paymentRequest = PaymentRequest::query()->create([
            'user_id' => $user->id,
            'description' => $data['description'],
            'invoice' => $invoicePath,
            'amount' => $amount,
            'currency' => $currency,
            'exchange_rate' => $exchangeData['rate'],
            'exchange_rate_source' => $exchangeData['source'],
            'exchanged_at' => $exchangeData['timestamp'],
            'eur_amount' => $eurAmount,
            'status' => PaymentRequestStatus::PENDING,
        ]);

        $paymentRequest->load('user');

        return $paymentRequest;
    }

    public function approve(PaymentRequest $paymentRequest, User $user): PaymentRequest
    {
        if ($paymentRequest->status !== PaymentRequestStatus::PENDING) {
            throw new BusinessRuleViolation('Only pending requests can be approved.');
        }

        $paymentRequest->update([
            'status' => PaymentRequestStatus::APPROVED,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        $paymentRequest->load(['user', 'approver']);

        return $paymentRequest;
    }

    public function reject(PaymentRequest $paymentRequest, User $user): PaymentRequest
    {
        if ($paymentRequest->status !== PaymentRequestStatus::PENDING) {
            throw new BusinessRuleViolation('Only pending requests can be rejected.');
        }

        $paymentRequest->update([
            'status' => PaymentRequestStatus::REJECTED,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        $paymentRequest->load(['user', 'approver']);

        return $paymentRequest;
    }
}
