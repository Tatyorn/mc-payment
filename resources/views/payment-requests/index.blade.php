@extends('layouts.app')
@section('title', __('payment.title_index'))
@section('content')
    <div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:shadow-md p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">{{ __('payment.new_request') }}</h2>
            </div>

            <form method="POST" action="{{ route('payment-requests.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                        <label for="description" class="block text-sm font-semibold text-gray-800">{{ __('payment.description') }}</label>
                        <div class="mt-1.5 relative rounded-lg shadow-sm">
                            <input type="text" name="description" id="description" value="{{ old('description') }}" required
                                   placeholder="{{ __('payment.description_placeholder') }}"
                                   class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-4 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        </div>
                        @error('description')
                        <p class="mt-1.5 text-sm text-destructive text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-800">{{ __('payment.amount') }}</label>
                        <div class="mt-1.5 relative rounded-lg shadow-sm">
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
                                   placeholder="{{ __('payment.amount_placeholder') }}"
                                   class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-4 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        </div>
                        @error('amount')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-semibold text-gray-800">{{ __('payment.currency') }}</label>
                        <div class="mt-1.5">
                            <select name="currency" id="currency" required
                                    class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-4 text-sm text-gray-900 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->value }}" {{ old('currency') === $currency->value ? 'selected' : '' }}>
                                        {{ $currency->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('currency')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-800 mb-1.5">{{ __('payment.invoice') }}</label>
                        <div class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 px-6 py-8 transition-colors hover:bg-gray-50">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v28a4 4 0 004 4h24a4 4 0 004-4V20L28 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M28 8v12h12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="invoice" class="relative cursor-pointer rounded-md bg-white font-medium text-purple-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-purple-500 focus-within:ring-offset-2 hover:text-purple-500">
                                        <span>{{ __('messages.file_choose') }}</span>
                                        <input type="file" name="invoice" id="invoice" accept=".pdf,.png,.jpg,.jpeg" required class="sr-only">
                                    </label>
                                    <p class="pl-1 text-gray-500" id="file-chosen">{{ __('messages.file_drag') }}</p>
                                </div>
                                <p class="text-xs text-gray-400">{{ __('messages.file_limits') }}</p>
                            </div>
                        </div>
                        @error('invoice')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center bg-purple-600 text-white py-2.5 px-6 rounded-lg hover:bg-purple-700 text-sm font-semibold shadow-sm transition-all focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        {{ __('payment.btn_create') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-5 gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('payment.history') }}</h2>
                </div>
                <form method="GET" action="{{ route('payment-requests.index') }}" class="flex items-center gap-2">
                    <label for="status-filter" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('payment.status_filter') }}</label>
                    <select name="status" id="status-filter" onchange="this.form.submit()"
                            class="rounded-lg border-gray-300 py-1.5 pl-3 pr-8 text-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">{{ __('payment.status_all') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($paymentRequests->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-400 text-sm">{{ __('payment.no_results') }}</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-6 sm:-mx-8">
                    <div class="inline-block min-w-full px-6 align-middle">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50/70">
                            <tr>
                                <th class="px-6 py-3.5 text-left font-semibold text-gray-500 uppercase tracking-wider">{{ __('payment.th_description') }}</th>
                                <th class="px-6 py-3.5 text-left font-semibold text-gray-500 uppercase tracking-wider">{{ __('payment.th_local_amount') }}</th>
                                <th class="px-6 py-3.5 text-left font-semibold text-gray-500 uppercase tracking-wider">{{ __('payment.th_converted_amount') }}</th>
                                <th class="px-6 py-3.5 text-left font-semibold text-gray-500 uppercase tracking-wider">{{ __('payment.th_status') }}</th>
                                <th class="px-6 py-3.5 text-left font-semibold text-gray-500 uppercase tracking-wider">{{ __('payment.th_date') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($paymentRequests as $pr)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $pr->description }}</td>
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap font-mono font-medium">{{ number_format($pr->amount, 2) }} {{ $pr->currency }}</td>
                                    <td class="px-6 py-4 text-gray-900 whitespace-nowrap font-mono font-semibold">{{ number_format($pr->eur_amount, 2) }} EUR</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-sm border
                                            {{ $pr->status->value === 'pending' ? 'bg-amber-50 text-amber-800 border-amber-200' : '' }}
                                            {{ $pr->status->value === 'approved' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : '' }}
                                            {{ $pr->status->value === 'rejected' ? 'bg-rose-50 text-rose-800 border-rose-200' : '' }}
                                            {{ $pr->status->value === 'expired' ? 'bg-gray-50 text-gray-600 border-gray-200' : '' }}">
                                            <span class="h-1.5 w-1.5 rounded-full
                                                {{ $pr->status->value === 'pending' ? 'bg-amber-500' : '' }}
                                                {{ $pr->status->value === 'approved' ? 'bg-emerald-500' : '' }}
                                                {{ $pr->status->value === 'rejected' ? 'bg-rose-500' : '' }}
                                                {{ $pr->status->value === 'expired' ? 'bg-gray-400' : '' }}"></span>
                                            {{ $pr->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $pr->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5 border-t border-gray-100 pt-4">
                    {{ $paymentRequests->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('invoice').addEventListener('change', function(e) {
            document.getElementById('file-chosen').textContent = e.target.files[0] ? e.target.files[0].name : "{{ __('messages.file_drag') }}";
        });
    </script>
@endsection
