<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.min.css">

    <div class="max-w-full mx-auto p-6">

        @if (session('success') || session('error'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 6000)"
                 x-transition:leave.duration.500ms
                 class="fixed top-20 right-6 z-50">

                <div class="bg-{{ session('success') ? 'green' : 'red' }}-100 border-l-4 border-{{ session('success') ? 'green' : 'red' }}-500 text-{{ session('success') ? 'green' : 'red' }}-700 p-4 rounded-md shadow-lg" role="alert">
                    <p class="font-bold">{{ session('success') ? 'Success!' : 'Error!' }}</p>
                    <p>{{ session('success') ?? session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold text-gray-800">My Property Listings</h1>

            <a href="{{ route('properties.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Add New Property
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-xl overflow-hidden p-4">
            <div class="overflow-x-auto">
                <table id="properties-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title / Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mod. Status</th> {{-- NEW COLUMN --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($properties as $property)
                            @php
                                $primaryImage = $property->images->where('is_primary', true)->first() ?? $property->images->first();

                                // Moderation Status Colors
                                $modStatusClass = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ][$property->moderation_status] ?? 'bg-gray-100 text-gray-800';

                                // Rental Status Colors (Existing logic)
                                $rentalStatusClass = [
                                    'available' => 'bg-green-100 text-green-800',
                                    'booked' => 'bg-red-100 text-red-800',
                                    'unavailable' => 'bg-gray-100 text-gray-800',
                                ][$property->status] ?? 'bg-gray-100 text-gray-800';

                            @endphp

                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($primaryImage)
                                        <img class="h-10 w-10 rounded object-cover"
                                              src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                              alt="{{ $property->title }}" loading="lazy">
                                    @else
                                        <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Image</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $property->city }}, {{ $property->address }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($property->rent, 2) }} / month
                                </td>

                                {{-- NEW MODERATION STATUS COLUMN --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $modStatusClass }}">
                                        {{ ucfirst($property->moderation_status) }}
                                    </span>
                                    @if ($property->moderation_status !== 'approved')
                                        <p class="text-xs text-red-500 mt-1">Not Live</p>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rentalStatusClass }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('properties.edit', $property) }}"
                                       class="text-indigo-600 hover:text-indigo-900
                                              {{ $property->moderation_status === 'pending' ? 'opacity-50 pointer-events-none' : '' }}"
                                       title="{{ $property->moderation_status === 'pending' ? 'Cannot edit while pending review' : 'Edit Property' }}">
                                       Edit
                                    </a>
                                    <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:text-blue-900">View</a>

                                    <form action="{{ route('properties.destroy', $property) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this property?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-lg">
                                    You have no properties listed yet. 😔 <br>
                                    <a href="{{ route('properties.create') }}" class="text-green-600 hover:underline mt-2 inline-block">Click here to add your first property.</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    @push('scripts')
    {{-- Removed unused video column from columnDefs --}}
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#properties-table').DataTable({
                responsive: true,
                order: [[ 1, 'asc' ]], // Sort by Title/Location column (index 1) by default
                columnDefs: [
                    { targets: [0, 4, 5], orderable: false } // Disable sorting on Image, Status, Actions
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>
