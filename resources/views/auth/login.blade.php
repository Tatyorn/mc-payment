@extends('layouts.app')
@section('title', __('auth.title_login'))
@section('content')
    <div class="min-h-[75vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-md space-y-6">

            <div class="text-center">
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
                    {{ __('auth.title_login') }}
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('auth.no_account') }}
                    <a href="{{ route('register') }}" class="font-semibold text-purple-600 hover:text-purple-500 transition-colors">
                        {{ __('auth.register_link') }}
                    </a>
                </p>
            </div>

            <div class="bg-white py-8 px-6 shadow-sm border border-gray-200 rounded-xl sm:px-10 transition-all duration-200 hover:shadow-md">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-s font-semibold text-gray-700 tracking-wider">
                            {{ __('auth.email') }}
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               placeholder="seu@email.com"
                               class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-s font-semibold text-gray-700 tracking-wider">
                            {{ __('auth.password') }}
                        </label>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="block w-full rounded-lg border-gray-300 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-purple-500 focus:bg-white focus:ring-purple-500">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-1 text-sm">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none">
                                Lembrar-me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div>
                                <a href="{{ route('password.request') }}" class="font-medium text-purple-600 hover:text-purple-500 transition-colors">
                                    Esqueceu a senha?
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center bg-purple-600 text-white py-2.5 px-4 rounded-lg hover:bg-purple-700 text-sm font-semibold shadow-sm transition-all focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;">
                            {{ __('auth.btn_login') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
