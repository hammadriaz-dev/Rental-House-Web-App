<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-lg">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Listing: {{ $property->title }}</h1>
            <a href="{{ route('properties.show', $property) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Details
            </a>
        </div>

        {{-- NEW MODERATION STATUS WARNING --}}
        @if ($property->moderation_status === 'pending')
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Pending Review</p>
                <p>This property is currently **pending admin review** and cannot be edited until approved or rejected. If you were redirected here after an update, your changes are pending approval.</p>
            </div>
        @elseif ($property->moderation_status === 'rejected')
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Listing Rejected</p>
                <p>This property has been **rejected** by an administrator. Please check your notifications for a rejection reason. You can make changes below to resubmit for review.</p>
            </div>
        @elseif ($property->moderation_status === 'approved')
             <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Approved</p>
                <p>⚠️ **Attention:** Any changes to the title, description, address, rent, images, or video will require the property to be **re-moderated** and will temporarily take the listing offline.</p>
            </div>
        @endif

        {{-- Check if the form should be disabled --}}
        @php
            $isDisabled = $property->moderation_status === 'pending';
        @endphp

        <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <fieldset {{ $isDisabled ? 'disabled' : '' }} class="{{ $isDisabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Property Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                    @error('title')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                        @error('address')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                        @error('city')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="rent" class="block text-sm font-medium text-gray-700">Rent ($)</label>
                        <input type="number" name="rent" id="rent" value="{{ old('rent', $property->rent) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                        @error('rent')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Rental Status (Public)</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ old('status', $property->status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t">
                    <h3 class="text-lg font-semibold text-gray-700">Current Media</h3>

                    <div class="flex flex-wrap gap-2 items-center">
                        @forelse ($property->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Property Image" class="w-20 h-20 object-cover rounded shadow">
                        @empty
                            <p class="text-sm text-gray-500">No images uploaded yet.</p>
                        @endforelse
                    </div>

                    @if ($property->video)
                        <p class="text-sm text-gray-600">Video uploaded: <span class="text-green-600">Yes</span></p>
                    @else
                        <p class="text-sm text-gray-600">Video uploaded: <span class="text-red-600">No</span></p>
                    @endif

                    <p class="text-xs text-yellow-600 font-medium">NOTE: Uploading new media will REPLACE the existing files.</p>
                </div>

                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700">Replace Images (Select 1 or more)</label>
                    <input type="file" name="images[]" id="images" multiple class="mt-1 block w-full text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">Only upload if you want to replace ALL current images.</p>
                    @error('images')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                    @error('images.*')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="video" class="block text-sm font-medium text-gray-700">Replace Video (Optional)</label>
                    <input type="file" name="video" id="video" class="mt-1 block w-full text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">Only upload if you want to replace the current video.</p>
                    @error('video')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end pt-4 border-t">
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ $property->moderation_status === 'rejected' ? 'Resubmit for Review' : 'Update Property' }}
                    </button>
                </div>
            </fieldset>
        </form>

    </div>
</x-app-layout>
