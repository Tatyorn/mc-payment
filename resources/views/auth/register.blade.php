@extends('layouts.app')
@section('title', __('auth.title_register'))
@section('content')
    <div class="min-h-[80vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
                    {{ __('auth.title_register') }}
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('auth.has_account') }}
                    <a href="{{ route('login') }}" class="font-semibold text-purple-600 hover:text-purple-500 transition-colors">
                        {{ __('auth.login_link') }}
                    </a>
                </p>
            </div>

            <div class="bg-white py-8 px-6 shadow-sm border border-gray-200 rounded-xl sm:px-10 transition-all duration-200 hover:shadow-md">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-s font-semibold text-gray-700  tracking-wider">
                            {{ __('auth.name') }}
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                               placeholder="Seu nome completo"
                               class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-s font-semibold text-gray-700  tracking-wider">
                            {{ __('auth.email') }}
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               placeholder="seu@email.com"
                               class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-s font-semibold text-gray-700  tracking-wider">
                            {{ __('auth.password') }}
                        </label>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-s font-semibold text-gray-700  tracking-wider">
                            {{ __('auth.password_confirmation') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="••••••••"
                               class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                    </div>

                    <div>
                        <label for="currency" class="block text-s font-semibold text-gray-700  tracking-wider">
                            {{ __('auth.currency') }}
                        </label>
                        <select name="currency" id="currency" required
                                class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->value }}" {{ old('currency') === $currency->value ? 'selected' : '' }}>
                                    {{ $currency->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center bg-purple-600 text-white py-2.5 px-4 rounded-lg hover:bg-purple-700 text-sm font-semibold shadow-sm transition-all focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            {{ __('auth.btn_register') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
