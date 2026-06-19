<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', __('messages.app_name')) }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('payment-requests.index') }}" class="text-xl font-bold text-indigo-600">
                        {{ __('messages.app_name') }}
                    </a>
                    @auth
                        <a href="{{ route('payment-requests.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('payment-requests.index') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-600 hover:text-gray-900' }} px-1 py-2">
                            {{ __('messages.nav_requests') }}
                        </a>
                        @can('finance', \App\Models\User::class)
                            <a href="{{ route('payment-requests.approval') }}"
                               class="text-sm font-medium {{ request()->routeIs('payment-requests.approval') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-600 hover:text-gray-900' }} px-1 py-2">
                                {{ __('messages.nav_approval') }}
                            </a>
                        @endcan
                    @endauth
                </div>
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('messages.nav_logout') }}
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
