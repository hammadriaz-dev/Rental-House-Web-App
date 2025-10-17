<x-app-layout>

<div class="max-w-full mx-auto">
    <!-- Dashboard Header/Title -->
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Landlord Dashboard</h1>

    <!-- Top Stat Cards (Property & Request Summary) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Total Properties Card -->
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500 uppercase">Total Properties</p>
            {{-- Dynamic Total Properties --}}
            <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalProperties ?? 0 }}</p>
            <a href="{{ route('properties.index') }}" class="text-xs text-green-600 mt-2 hover:underline font-semibold">View All</a>
        </div>

        <!-- Pending Booking Requests Card -->
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500 uppercase">Pending Requests</p>
            {{-- Dynamic Pending Requests Count --}}
            <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $pendingRequestsCount ?? 0 }}</p>
            <a href="{{ route('booking.requests', ['status' => 'pending']) }}" class="text-xs text-yellow-600 mt-2 hover:underline font-semibold">Review Now</a>
        </div>

        <!-- Occupied Properties Card -->
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-500 uppercase">Currently Occupied</p>
            {{-- Dynamic Occupied Properties Count --}}
            <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $occupiedPropertiesCount ?? 0 }}</p>
            <a href="{{ route('properties.index', ['status' => 'occupied']) }}" class="text-xs text-blue-600 mt-2 hover:underline font-semibold">View Details</a>
        </div>
    </div>

    <!-- Main Content Grid: Booking Requests & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Recent/Pending Booking Requests -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Pending Booking Requests (Recent)</h2>
                <a href="{{ route('booking.requests', ['status' => 'pending']) }}" class="text-sm text-green-600 hover:text-green-800">View All Requests</a>
            </div>

            <!-- Booking Requests Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                        {{-- Dynamic Loop for Pending Requests --}}
                        @forelse ($pendingRequests as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->property->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    {{-- Actions should link to a booking review page --}}
                                    <a href="{{ route('booking.requests', $booking) }}" class="text-blue-600 hover:text-blue-900">Review</a>
                                    <a href="#" class="text-green-600 hover:text-green-900">Accept</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Reject</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    No pending booking requests at this time.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Quick Actions and Account -->
        <div class="lg:col-span-1 space-y-8">

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('properties.create') }}" class="flex items-center p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-150">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium text-gray-700">Add a New Property</span>
                    </a>
                    <a href="{{ route('landlord.messages.app') }}" class="flex items-center p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-150">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        <span class="font-medium text-gray-700">Message Tenants</span>
                    </a>
                    <a href="{{ route('landlord.payments.index') }}" class="flex items-center p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-150">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="font-medium text-gray-700">View Payments</span>
                    </a>
                </div>
            </div>

            <!-- Account & Profile Link (Kept static as links are based on Laravel Auth routes) -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Account</h3>
                <div class="space-y-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center text-sm text-gray-700 hover:text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path></svg>
                        Edit Profile Information
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center text-sm text-red-500 hover:text-red-700 w-full text-left">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-5m3-8h5a3 3 0 013 3v1"></path></svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
