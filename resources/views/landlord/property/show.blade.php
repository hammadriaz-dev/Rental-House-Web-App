<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $property->title }}</h1>
            <div class="space-x-4">
                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-9-4l9-9m-3 9l9-9"></path></svg>
                    Edit Listing
                </a>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 font-medium">
                    Back to Listings
                </a>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-xl p-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div x-data="{ currentImage: 0, images: @js($property->images->pluck('image_path')) }" class="md:col-span-2 relative aspect-video rounded-lg overflow-hidden border-4 border-gray-100">

                    @forelse ($property->images as $index => $image)
                        <img
                            x-show="currentImage === {{ $index }}"
                            x-transition.opacity
                            src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $property->title }} Image {{ $index + 1 }}"
                            class="absolute top-0 left-0 w-full h-full object-cover">
                    @empty
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">No Images Uploaded</div>
                    @endforelse

                    @if ($property->images->count() > 1)
                        <button
                            x-show="currentImage > 0"
                            @click="currentImage = currentImage > 0 ? currentImage - 1 : images.length - 1"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 p-3 bg-black bg-opacity-50 text-white rounded-full hover:bg-opacity-75 transition z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>

                        <button
                            x-show="currentImage < images.length - 1"
                            @click="currentImage = currentImage < images.length - 1 ? currentImage + 1 : 0"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 p-3 bg-black bg-opacity-50 text-white rounded-full hover:bg-opacity-75 transition z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    @endif

                    <span class="absolute bottom-3 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white text-xs font-semibold px-3 py-1 rounded-full z-10">
                        <span x-text="currentImage + 1"></span> of <span x-text="images.length"></span> Photos
                    </span>

                </div>
                <div class="md:col-span-1 space-y-4">
                    <h3 class="text-lg font-bold text-gray-700">Key Information</h3>

                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-500">Monthly Rent</p>
                        <p class="text-xl font-extrabold text-green-600">${{ number_format($property->rent, 2) }}</p>
                    </div>

                    <div class="border-b pb-2">
                        <p class="text-sm text-gray-500">Current Status</p>
                        <p class="text-lg font-semibold
                            {{ $property->status == 'available' ? 'text-green-600' : '' }}
                            {{ $property->status == 'booked' ? 'text-red-600' : '' }}
                            {{ $property->status == 'unavailable' ? 'text-gray-600' : '' }}">
                            {{ ucfirst($property->status) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Video Tour</p>
                        @if ($property->video)
                            <a href="{{ asset('storage/' . $property->video) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.552-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.448.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Watch Video
                            </a>
                        @else
                            <p class="text-gray-400">Not provided</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t">
                <h3 class="text-xl font-bold text-gray-700 mb-3">Property Description</h3>
                <p class="text-gray-600 leading-relaxed">{{ $property->description }}</p>
            </div>

            <div class="pt-4 border-t">
                <h3 class="text-xl font-bold text-gray-700 mb-3">Location</h3>
                <p class="text-gray-600">{{ $property->address }}, {{ $property->city }}</p>
            </div>

        </div>
    </div>
</x-app-layout>
