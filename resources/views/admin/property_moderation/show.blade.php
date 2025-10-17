@php
    // Helper function for status badge colors
    $statusColor = [
        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
        'approved' => 'bg-green-100 text-green-800 border-green-500',
        'rejected' => 'bg-red-100 text-red-800 border-red-500',
    ][$property->moderation_status] ?? 'bg-gray-100 text-gray-800 border-gray-500';

    $primaryImage = $property->images->where('is_primary', true)->first() ?? $property->images->first();
@endphp

<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-start mb-8 border-b pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Review Property: {{ $property->title }}
                </h1>
                <div class="mt-2 flex items-center space-x-3">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $statusColor }}">
                        Moderation Status: {{ ucfirst($property->moderation_status) }}
                    </span>
                    <p class="text-gray-500 text-sm">Submitted by: <span class="font-medium text-indigo-600">{{ $property->user->name }}</span> ({{ $property->user->email }})</p>
                </div>
            </div>
            <a href="{{ route('moderation.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Moderation List
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert"><p>{{ session('success') }}</p></div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert"><p>{{ session('error') }}</p></div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Property Details Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Images/Media Section --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Media Review</h2>

                    <h3 class="font-medium text-gray-700 mb-2">Images ({{ $property->images->count() }}):</h3>
                    @if ($property->images->count() > 0)
                        <div class="flex flex-wrap gap-3 mb-4">
                            @foreach ($property->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Property Image" class="w-24 h-24 object-cover rounded shadow-md {{ $image->is_primary ? 'border-2 border-indigo-600' : '' }}" title="{{ $image->is_primary ? 'Primary Image' : 'Gallery Image' }}">
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-red-500">No images provided.</p>
                    @endif

                    <h3 class="font-medium text-gray-700 mb-2 mt-4">Video:</h3>
                    @if ($property->video)
                        <video controls class="w-full max-h-96 rounded-lg shadow-lg bg-black">
                            <source src="{{ asset('storage/' . $property->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <p class="text-sm text-gray-500">No video uploaded.</p>
                    @endif
                </div>

                {{-- Textual Details Section --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Listing Details</h2>
                    <dl class="divide-y divide-gray-200">
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $property->title }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Rent</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 text-lg font-bold text-green-600">${{ number_format($property->rent, 2) }} / month</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $property->address }}, {{ $property->city }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Rental Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ ucfirst($property->status) }}</dd>
                        </div>
                        <div class="py-4">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                            <dd class="text-sm text-gray-900 leading-relaxed">{{ $property->description }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Action Panel (1/3 width) --}}
            <div class="lg:col-span-1 space-y-6">

                @if ($property->moderation_status === 'pending')

                    {{-- Approve Action --}}
                    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-lg font-bold text-green-600 mb-4">Approve Listing</h3>
                        <p class="text-sm text-gray-600 mb-4">If all details and media are satisfactory, approve the listing to make it public and searchable.</p>

                        <form method="POST" action="{{ route('moderation.approve', $property->id) }}" onsubmit="return confirm('ARE YOU SURE you want to APPROVE this property?');">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Approve Property
                            </button>
                        </form>
                    </div>

                    {{-- Reject Action --}}
                    <div class="bg-white shadow rounded-lg p-6 border-l-4 border-red-500">
                        <h3 class="text-lg font-bold text-red-600 mb-4">Reject Listing</h3>
                        <p class="text-sm text-gray-600 mb-4">Rejecting will make the listing inaccessible to the public and inform the landlord to make corrections.</p>

                        <form method="POST" action="{{ route('moderation.reject', $property->id) }}" onsubmit="return confirm('ARE YOU SURE you want to REJECT this property?');">
                            @csrf
                            {{-- Add an optional field for the rejection reason here, if you have a place to store it (e.g., a 'rejection_note' column) --}}
                            {{-- For now, we rely on the confirmation and assume the admin will notify the user separately if needed. --}}
                            <button type="submit" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reject Property
                            </button>
                        </form>
                    </div>

                @else
                    {{-- Status History (if not pending) --}}
                    <div class="bg-white shadow rounded-lg p-6 border-l-4 {{ $property->moderation_status === 'approved' ? 'border-green-500' : 'border-red-500' }}">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Listing Status</h3>
                        <p class="text-sm text-gray-600">This property's moderation status is currently **{{ ucfirst($property->moderation_status) }}**.</p>
                        @if ($property->moderation_status === 'approved')
                            <p class="text-sm mt-2 text-green-700">The listing is live on the site.</p>
                        @elseif ($property->moderation_status === 'rejected')
                            <p class="text-sm mt-2 text-red-700">The listing is NOT live. The landlord needs to make corrections and resubmit.</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-4">Last updated: {{ $property->updated_at->diffForHumans() }}</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
