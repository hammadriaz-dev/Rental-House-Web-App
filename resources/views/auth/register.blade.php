<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />
        <div class="text-center mb-6">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Your Company Logo" class="mx-auto h-16 w-auto"> {{-- Adjust h-16 as needed for size --}}
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Create Your Account</h1>
            <p class="text-gray-500 mt-1">Join us to find or manage properties.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                {{-- Removed <x-input-label> --}}
                <x-text-input id="name" name="name" type="text" placeholder="Full Name"
                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                {{-- Removed <x-input-label> --}}
                <x-text-input id="email" name="email" type="email" placeholder="Email Address"
                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                {{-- Removed <x-input-label> --}}
                <x-text-input id="CNIC" name="CNIC" type="text" placeholder="CNIC (e.g., 12345-6789012-3)"
                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :value="old('CNIC')" required />
                <x-input-error :messages="$errors->get('CNIC')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Select Your Role')" />
                <select id="role" name="role" required
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5">
                    <option value="" disabled selected>Choose your role...</option>
                    {{-- Note: We assume $roles is passed to the view --}}
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    {{-- Removed <x-input-label> --}}
                    <x-text-input id="password" name="password" type="password" placeholder="Password"
                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    {{-- Removed <x-input-label> --}}
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password"
                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="pt-4">
                <x-primary-button class="w-full justify-center rounded-lg py-3 text-lg font-semibold bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    {{ __('Create Account') }}
                </x-primary-button>
            </div>


            <p class="text-sm text-center text-gray-500 pt-2">
                {{ __("Already have an account?") }}
                <a class="font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150" href="{{ route('login') }}">
                    {{ __('Login here') }}
                </a>
            </p>
        </form>
        
</x-guest-layout>
