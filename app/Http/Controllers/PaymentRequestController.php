<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Enums\PaymentRequestStatus;
use App\Http\Requests\StorePaymentRequestRequest;
use App\Http\Resources\PaymentRequestResource;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Services\PaymentRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentRequestController
{
    public function __construct(
        private readonly PaymentRequestService $paymentRequestService,
    ) {}

    /**
     * List payment requests.
     *
     * Returns a paginated list of payment requests. Employees only see their own.
     * Finance users see all. Optionally filter by status.
     *
     * @queryParam status string Filter by status (pending, approved, rejected, expired). Example: pending
     */
    public function index(Request $request): View|JsonResource
    {
        $query = PaymentRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (auth()->user()->role->isEmployee()) {
            $query->where('user_id', auth()->user()->id);
        }

        if ($request->expectsJson()) {
            return PaymentRequestResource::collection($query->latest()->paginate(15));
        }

        $paymentRequests = $query->latest()->paginate(15);
        $currencies = Currency::cases();
        $statuses = PaymentRequestStatus::cases();

        return view('payment-requests.index', compact('paymentRequests', 'currencies', 'statuses'));
    }

    /**
     * Create a payment request.
     *
     * Creates a new payment request with automatic currency conversion to EUR.
     */
    public function store(StorePaymentRequestRequest $request): JsonResource|RedirectResponse
    {
        $paymentRequest = $this->paymentRequestService->create(
            $request->validated(),
            auth()->user(),
        );

        if ($request->expectsJson()) {
            return PaymentRequestResource::make($paymentRequest);
        }

        return redirect(route('payment-requests.index'))
            ->with('success', __('payment.created_success'));
    }

    /**
     * Get a payment request.
     *
     * Returns the details of a specific payment request.
     */
    public function show(PaymentRequest $paymentRequest): JsonResource
    {
        $paymentRequest->load(['user', 'approver']);

        return PaymentRequestResource::make($paymentRequest);
    }

    public function approvalIndex(): View
    {
        Gate::authorize('finance', User::class);

        $pendingRequests = PaymentRequest::with('user')
            ->where('status', PaymentRequestStatus::PENDING)
            ->latest()
            ->paginate(15);

        return view('payment-requests.approval', compact('pendingRequests'));
    }

    /**
     * Approve a payment request.
     *
     * Finance users only. Approves a pending payment request.
     */
    public function approve(PaymentRequest $paymentRequest, Request $request): JsonResource|RedirectResponse
    {
        Gate::authorize('finance', User::class);

        $paymentRequest = $this->paymentRequestService->approve($paymentRequest, auth()->user());

        if ($request->expectsJson()) {
            return PaymentRequestResource::make($paymentRequest);
        }

        return redirect(route('payment-requests.approval'))
            ->with('success', __('payment.approved_success'));
    }

    /**
     * Reject a payment request.
     *
     * Finance users only. Rejects a pending payment request.
     */
    public function reject(PaymentRequest $paymentRequest, Request $request): JsonResource|RedirectResponse
    {
        Gate::authorize('finance', User::class);

        $this->paymentRequestService->reject($paymentRequest, auth()->user());

        if ($request->expectsJson()) {
            return PaymentRequestResource::make($paymentRequest);
        }

        return redirect(route('payment-requests.approval'))
            ->with('success', __('payment.rejected_success'));
    }
}
