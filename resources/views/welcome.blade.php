<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MC Payment') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-indigo-600 mb-4">{{ __('messages.app_name') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('messages.welcome_subtitle') }}</p>
            <a href="{{ route('login') }}" class="bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                {{ __('auth.btn_login') }}
            </a>
        </div>
    </div>
</body>
</html>
