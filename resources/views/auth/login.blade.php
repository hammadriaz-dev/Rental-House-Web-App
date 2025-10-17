<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="text-center mb-6">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Your Company Logo" class="mx-auto h-24 w-auto">
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Welcome Back</h1>
            <p class="text-gray-500 mt-1">Sign in to manage your properties.</p>
        </div>

        <div>
            {{-- Removed <x-input-label> --}}
            <x-text-input
                id="email"
                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="Email Address" {{-- Added placeholder --}}
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            {{-- Removed <x-input-label> --}}
            <x-text-input
                id="password"
                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                type="password"
                name="password"
                placeholder="Password" {{-- Added placeholder --}}
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">

            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-900 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center rounded-lg py-3 text-lg font-semibold bg-indigo-600 hover:bg-indigo-700 transition duration-150">
            {{ __('Log In Securely') }}
        </x-primary-button>

        @if (Route::has('register'))
            <p class="text-sm text-center text-gray-500 pt-2">
                {{ __("Need an account?") }}
                <a class="font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150" href="{{ route('register') }}">
                    {{ __('Register here') }}
                </a>
            </p>
        @endif
    </form>
    
</x-guest-layout>
