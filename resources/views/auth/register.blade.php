<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-lg rounded-xl p-8 border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Create Your Account 🚀</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" placeholder="John Doe"
                    class="block mt-1 w-full"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" name="email" type="email" placeholder="you@example.com"
                    class="block mt-1 w-full"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- CNIC -->
            <div>
                <x-input-label for="CNIC" :value="__('CNIC (e.g., 12345-6789012-3)')" />
                <x-text-input id="CNIC" name="CNIC" type="text" placeholder="12345-6789012-3"
                    class="block mt-1 w-full"
                    :value="old('CNIC')" required />
                <x-input-error :messages="$errors->get('CNIC')" class="mt-2" />
            </div>

            <!-- Role -->
            <div>
                <x-input-label for="role" :value="__('Select Your Role')" />
                <select id="role" name="role" required
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5">
                    <option value="" disabled selected>Choose a role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Passwords -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" name="password" type="password" placeholder="••••••••"
                        class="block mt-1 w-full"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••"
                        class="block mt-1 w-full"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    {{ __('Already registered? Login') }}
                </a>
                <x-primary-button class="ms-4 px-6 py-2">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
