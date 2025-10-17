@php
    use Carbon\Carbon;
@endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <span class="text-indigo-600">Admin Panel:</span> Property Moderation ({{ $properties->total() }})
            </h1>
            <p class="text-gray-500">List of properties pending review.</p>
        </div>

        {{-- Status Notification --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner/Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Status</th> {{-- Display existing status --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted On</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($properties as $property)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $property->title }}
                                        <div class="text-xs text-gray-400 truncate">{{ Str::limit($property->address ?? 'No Address Given', 40) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $property->user->name ?? 'N/A' }}
                                    </td>
                                    {{-- Display existing rental status for context --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $property->status === 'available' ? 'bg-green-100 text-green-800' : ($property->status === 'booked' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Carbon::parse($property->created_at)->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                        {{-- 1. View Detail Link --}}
                                        <a href="{{ route('moderation.show', $property->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Review</a>

                                        {{-- 2. Approve Form --}}
                                        <form method="POST" action="{{ route('moderation.approve', $property->id) }}" class="inline-block" onsubmit="return confirm('Confirm APPROVAL for property: {{ $property->title }}?');">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-medium ml-2">Approve</button>
                                        </form>

                                        {{-- 3. Reject Button --}}
                                        <form method="POST" action="{{ route('moderation.reject', $property->id) }}" class="inline-block" onsubmit="return confirm('Confirm REJECTION for property: {{ $property->title }}?');">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium ml-2">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-lg text-gray-500">
                                        🎉 No properties currently pending moderation.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
