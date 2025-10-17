@php
    use Carbon\Carbon;
@endphp
<x-app-layout>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <span class="text-indigo-600">Admin Panel:</span> User Management
            </h1>
            {{-- Link to create a new user --}}
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Add New User
            </a>
        </div>

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


        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Registered</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Use Spatie's method to get the FIRST role assigned --}}
                                        @php
                                            $roleName = $user->getRoleNames()->first(); // Get the primary role name
                                        @endphp

                                        @if ($roleName)
                                            @php
                                                $roleClasses = match ($roleName) {
                                                    'admin' => 'bg-indigo-100 text-indigo-800',
                                                    'landlord' => 'bg-green-100 text-green-800',
                                                    default => 'bg-blue-100 text-blue-800', // Tenant or other roles
                                                };
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClasses }} capitalize">
                                                {{ $roleName }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                No Role Assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Carbon::parse($user->created_at)->toFormattedDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">
                                        {{-- Actions for Edit and Delete --}}
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>

                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                            class="inline-block"
                                            onsubmit="return confirm('WARNING: Are you sure you want to PERMANENTLY delete this user ({{ $user->email }})?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 font-medium ml-2">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-lg text-gray-500">
                                        No users found in the system.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>
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
