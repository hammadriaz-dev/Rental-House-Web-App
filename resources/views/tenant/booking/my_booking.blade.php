<x-app-layout>

<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">My Bookings</h1>
    <hr class="mb-6">

    @forelse ($bookings as $booking)
    {{-- Booking Card: Uses shadow and rounded corners --}}
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-4 md:p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">

            {{-- Property Image Container --}}
            <div class="w-full md:w-1/4 flex-shrink-0">
                @php
                    // Safely get the primary image path
                    $imagePath = optional($booking->property->images->first(fn($img) => $img->is_primary))->image_path
                                 ?? optional($booking->property->images->first())->image_path;
                @endphp
                @if ($imagePath)
                    {{-- Tailwind class for full width and controlled height --}}
                    <img src="{{ asset('storage/' . $imagePath) }}"
                         alt="{{ $booking->property->title }}"
                         class="w-full h-40 object-cover rounded-md">
                @else
                    <div class="w-full h-40 flex items-center justify-center bg-gray-100 text-gray-500 rounded-md">
                        [No Image]
                    </div>
                @endif
            </div>

            {{-- Booking Details Container --}}
            <div class="w-full md:w-3/4">
                <h5 class="text-xl font-semibold text-black mb-1">{{ $booking->property->title }}</h5>
                <p class="text-sm text-gray-500 mb-4">{{ $booking->property->address }}, {{ $booking->property->city }}</p>

                {{-- Key Details Grid --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-3 mb-4">
                    {{-- Status --}}
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block">Status:</strong>
                        @php
                            $status = strtolower($booking->status);
                            $badgeClass = match ($status) {
                                'confirmed' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    {{-- Dates --}}
                    <div class="col-span-1">
                        <strong class="text-sm font-medium text-gray-700 block">Move-in:</strong>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="col-span-1">
                        <strong class="text-sm font-medium text-gray-700 block">Move-out:</strong>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="flex space-x-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('property_deatils', $booking->property->id) }}" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-sm font-medium rounded-md text-white bg-black hover:bg-indigo-50 hover:text-black transition duration-150">
                        View Property
                    </a>
                    {{-- Chat with Landlord Button --}}
                    {{-- <a href="{{ route('tenant.messages.show', $booking->property->user_id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        Chat with Landlord
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <p class="text-lg text-gray-600">You currently have no active or pending bookings.</p>
        </div>
    @endforelse

    {{-- Pagination Links (styled by Tailwind/Laravel) --}}
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>

</x-app-layout>
