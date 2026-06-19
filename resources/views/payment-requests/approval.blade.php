@extends('layouts.app')
@section('title', __('payment.title_approval'))
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">{{ __('payment.heading_approval') }}</h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold mb-4">{{ __('payment.pending_requests') }}</h2>

        @if($pendingRequests->isEmpty())
            <p class="text-gray-500 text-sm">{{ __('payment.no_pending') }}</p>
        @else
            <div class="space-y-4">
                @foreach($pendingRequests as $pr)
                    <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-medium">{{ $pr->description }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $pr->user->name }} {{ __('payment.user_prefix') }} {{ number_format($pr->amount, 2) }} {{ $pr->currency }}
                                ({{ number_format($pr->eur_amount, 2) }} EUR)
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $pr->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <form method="POST" action="{{ route('payment-requests.approve', $pr) }}">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white py-1.5 px-4 rounded-lg hover:bg-green-700 text-sm font-medium">
                                    {{ __('payment.btn_approve') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('payment-requests.reject', $pr) }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white py-1.5 px-4 rounded-lg hover:bg-red-700 text-sm font-medium">
                                    {{ __('payment.btn_reject') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $pendingRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
