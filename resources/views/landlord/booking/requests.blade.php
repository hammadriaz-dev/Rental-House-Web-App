@php
    // Import Carbon for date formatting
    use Carbon\Carbon;
@endphp
<x-app-layout>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">
            <span class="text-green-600">Property</span> Booking Requests
        </h1>

        <!-- START: Status Notification -->
        @if (session('success'))
            <div id="status-notification" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div id="status-notification" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        <!-- END: Status Notification -->
        
        @forelse ($propertiesWithBookings as $property)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-10 border-t-4 border-blue-500">
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Requests for: {{ $property->title }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ $property->address }}</p>
                </div>

                <div class="p-6">
                    @forelse ($property->bookings as $booking)
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 mb-4 border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex-grow mb-4 md:mb-0 space-y-1">
                                <p class="text-sm font-medium text-gray-500 uppercase">
                                    Requested by: <span class="text-lg font-bold text-indigo-700">{{ $booking->user->name ?? 'Unknown Tenant' }}</span>
                                </p>
                                <p class="text-gray-700 text-sm">
                                    <span class="font-semibold">Start Date:</span> {{ Carbon::parse($booking->start_date)->toFormattedDateString() }}
                                </p>
                                <p class="text-gray-700 text-sm">
                                    <span class="font-semibold">End Date:</span> {{ Carbon::parse($booking->end_date)->toFormattedDateString() }}
                                </p>
                                <p class="text-gray-700 text-sm">
                                    <span class="font-semibold">Duration:</span>
                                    {{ Carbon::parse($booking->start_date)->diffInDays(Carbon::parse($booking->end_date)) }} days
                                </p>
                            </div>

                            <div class="flex space-x-3">
                                {{-- Approve Button Form --}}
                                <form method="POST" action="{{ route('bookings.approve', $booking->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Approve
                                    </button>
                                </form>

                                {{-- Reject Button Form --}}
                                <form method="POST" action="{{ route('bookings.reject', $booking->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 p-4 bg-gray-50 rounded-lg">
                            No pending booking requests for {{ $property->title }}.
                        </p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="text-center p-10 bg-white rounded-lg shadow-xl">
                <p class="text-xl text-gray-500">You currently have no pending booking requests across any of your properties.</p>
                <a href="{{ route('properties.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium">View your properties</a>
            </div>
        @endforelse
    </div>
</x-app-layout>
