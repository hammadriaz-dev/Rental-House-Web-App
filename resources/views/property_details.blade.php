@extends('layout.app')

@section('content')
<div class="container">
<div class="my-8">
    <div class="header-section mb-4">
        <h1 class="text-3xl font-bold">{{ $property->title }}</h1>
        <p class="text-gray-500">{{ $property->address }}, {{ $property->city }}</p>
    </div>

    {{-- Property Image Gallery --}}
<div class="image-gallery grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @forelse ($property->images as $image)
        <img src="{{ asset('storage/' . $image->image_path) }}"
             alt="{{ $property->title }} image"
             class="w-full h-64 object-cover rounded-lg shadow-md">
    @empty
        <div class="col-span-full text-center p-8 bg-gray-100 rounded-lg">
            <p>No images available for this property.</p>
        </div>
    @endforelse
</div>

    <div class="content-wrapper flex flex-col lg:flex-row gap-8">

        {{-- Main Details Column --}}
        <div class="main-details w-full lg:w-2/3">

            <h2 class="text-2xl font-semibold mb-3 border-b pb-2">Description</h2>
            <p class="text-gray-700 mb-6 leading-relaxed">{{ $property->description }}</p>

            <div class="price-box bg-green-50 p-4 rounded-lg mb-6 border border-green-200">
                <h3 class="text-xl font-bold text-green-700">Monthly Rent: ${{ number_format($property->rent, 0) }}</h3>
                <p class="text-green-600">Status: {{ ucfirst($property->status) }}</p>
            </div>

            {{-- Property Features/Amenities (Add more features here if you have columns for them) --}}
            <h2 class="text-2xl font-semibold mb-3 border-b pb-2">Key Information</h2>
            <ul class="list-disc pl-5 text-gray-700">
                <li>Address: {{ $property->address }}</li>
                <li>City: {{ $property->city }}</li>
                <li>Listed Since: {{ $property->created_at->format('M d, Y') }}</li>
            </ul>

        </div>

        {{-- Sidebar / Actions Column --}}
        <div class="sidebar w-full lg:w-1/3 p-4 bg-gray-50 rounded-lg shadow-inner sticky top-0">

            <h3 class="text-xl font-semibold mb-4">Ready to Move In?</h3>

            {{-- Action Buttons --}}
            <div class="action-buttons space-y-3">

                {{-- Book Now Link (Connects to your booking flow) --}}
                @auth
                    @role('tenant')
                        <a href="{{ route('booking.create', $property->id) }}" class="btn-primary w-full block text-center py-3 rounded-md font-medium bg-black text-white hover:bg-white hover:text-black transition duration-150">
                            Book Now
                        </a>
                        <a href="{{ route('tenant.messages.app', $property->user_id) }}" class="btn-secondary w-full block text-center py-3 rounded-md font-medium border border-gray-300 bg-white hover:bg-gray-50 transition duration-150">
                            Chat with Landlord
                        </a>
                    @endrole
                @else
                    {{-- For guests: Prompt to log in/register --}}
                    <p class="text-sm text-center text-gray-600">You must be logged in to book or chat.</p>
                    <a href="{{ route('login') }}" class="btn-primary w-full block text-center py-3 rounded-md font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition duration-150">
                        Login to Book
                    </a>
                    <a href="{{ route('register') }}" class="btn-secondary w-full block text-center py-3 rounded-md font-medium border border-gray-300 bg-white hover:bg-gray-50 transition duration-150">
                        Register
                    </a>
                @endauth
            </div>

            {{-- Landlord Information --}}
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h4 class="font-semibold mb-2">Property Managed By:</h4>
                <p class="text-gray-700">{{ $property->user->name ?? 'Unknown Owner' }}</p>
                {{-- Optionally show owner's profile picture or contact details here --}}
            </div>
        </div>

    </div>
</div>
</div>
@endsection
