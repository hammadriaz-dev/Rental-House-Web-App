<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">
            <span class="text-indigo-600">Admin Panel:</span> Create New User
        </h1>
        {{-- Status Notification --}}
        @if (session('success') || session('error') || $errors->any())
            @if (session('success'))
                <div id="status-notification"
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md"
                    role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div id="status-notification"
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md"
                    role="alert">
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div id="status-notification"
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md"
                    role="alert">
                    <p class="font-bold">Validation Error!</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="space-y-6">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CNIC --}}
                    <div>
                        <label for="CNIC" class="block text-sm font-medium text-gray-700">CNIC</label>
                        <input type="text" name="CNIC" id="CNIC" value="{{ old('CNIC') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('CNIC')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Auto-disappearing notification script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('status-notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.transition = 'opacity 1s ease-out';
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.remove();
                    }, 1000);
                }, 3000);
            }
        });
    </script>
</x-app-layout>
