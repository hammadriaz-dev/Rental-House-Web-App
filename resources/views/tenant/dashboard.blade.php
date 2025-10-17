@php
    // FIX: Import the Carbon class for use within the Blade template
    use Carbon\Carbon;
@endphp
<x-app-layout>

    <div class="max-w-full mx-auto">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Welcome back, {{ Auth::user()->name }}!</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Card 1: Current Rental --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-500 uppercase">Current Rental</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">
                    {{ $currentRentalData['title'] ?? 'No Active Rental' }}
                </p>
                <div class="text-xs text-gray-500 mt-2">
                    {{-- The controller already sets 'lease_end_date' using the correct 'end_date' column. --}}
                    <p>Lease ends: **{{ $currentRentalData['lease_end_date'] ?? 'N/A' }}**</p>
                    <p>Status:
                        <span class="text-{{ $currentRentalData ? 'green-600' : 'gray-600' }} font-semibold">
                            {{ $currentRentalData['status'] ?? 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Card 2: Next Payment Due --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                <p class="text-sm font-medium text-gray-500 uppercase">Next Payment Due</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">
                    @if($nextPaymentData['amount'])
                        ${{ $nextPaymentData['amount'] }} <span class="text-base font-normal text-gray-500">/mo</span>
                    @else
                        N/A
                    @endif
                </p>
                <p class="text-xs text-{{ $nextPaymentData['status_color'] }}-500 mt-2 font-semibold">
                    {{ $nextPaymentData['status_text'] }}
                </p>
            </div>

            {{-- Card 3: Unread Messages --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-gray-500 uppercase">Unread Messages</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">
                    {{ $unreadMessageCount }}
                    <span class="text-base font-normal text-gray-500">from Landlord</span>
                </p>
                {{-- Assuming a route for tenant messages --}}
                <a href="{{ route('tenant.messages.app') }}" class="text-xs text-yellow-600 mt-2 hover:underline font-semibold">
                    View Messages
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Main Content: Recent & Upcoming Bookings --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Recent & Upcoming Bookings</h2>
                    {{-- Assuming a route for viewing all bookings --}}
                    <a href="{{ route('my_booking') }}" class="text-sm text-green-600 hover:text-green-800">View All Bookings</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                {{-- FIX: Changed 'Move-in' to 'Start Date' for clarity/consistency --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($recentBookings as $booking)
                                @php
                                    $statusClasses = [
                                        'Active' => 'bg-green-100 text-green-800',
                                        'Confirmed' => 'bg-blue-100 text-blue-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Cancelled' => 'bg-red-100 text-red-800',
                                        'Past' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $class = $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $booking->property->title ?? 'N/A' }}
                                    </td>
                                    {{-- FIX: Using the correct 'start_date' column. --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Carbon::parse($booking->start_date)->toFormattedDateString() }}
                                    </td>
                                    {{-- FIX: Using the correct 'rent' column from the property schema. --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($booking->property->rent ?? 0, 2) }}/mo
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        {{-- Actions based on Status --}}
                                        @if ($booking->status === 'Pending')
                                            <a href="{{ route('tenant.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">Review</a>
                                        @elseif ($booking->status === 'Confirmed' || $booking->status === 'Active')
                                            <a href="{{ route('property_deatils', $booking->property->id) }}" class="text-green-600 hover:text-green-900">View Property</a>
                                        @elseif ($booking->status === 'Past')
                                            {{-- Review is skipped for now per user request --}}
                                            <span class="text-gray-500">No Review Option</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        You have no recent booking history.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sidebar Content --}}
            <div class="lg:col-span-1 space-y-8">

                {{-- Quick Actions --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        {{-- Make a Payment --}}
                        <a href="" class="flex items-center p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-150">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="font-medium text-gray-700">Make a Payment</span>
                        </a>
                        {{-- Contact Landlord --}}
                        <a href="{{ route('tenant.messages.app') }}" class="flex items-center p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition duration-150">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            <span class="font-medium text-gray-700">Contact Landlord</span>
                        </a>
                        {{-- Submit Review (Disabled) --}}
                        <span class="flex items-center p-3 bg-gray-100 rounded-lg text-gray-400 cursor-not-allowed">
                            <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.05 13.5l3.85-3.85M13.5 11.05l-3.85 3.85M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium">Submit Review (Pending)</span>
                        </span>
                    </div>
                </div>

                {{-- Account Actions --}}
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
