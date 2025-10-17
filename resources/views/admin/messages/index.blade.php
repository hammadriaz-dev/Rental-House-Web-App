<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Message Monitoring Panel</h1>
        <p class="mb-4 text-gray-600">This view shows the most recent message from every conversation thread for moderation purposes.</p>

        <div class="bg-white shadow-xl rounded-xl overflow-hidden divide-y divide-gray-200">
            <div class="p-4 bg-gray-100 font-semibold text-gray-700 grid grid-cols-12">
                <div class="col-span-3">Thread Participants</div>
                <div class="col-span-2">Role 1 | Role 2</div>
                <div class="col-span-4">Property Link</div>
                <div class="col-span-3 text-right">Last Message Time</div>
            </div>

            @forelse ($threads as $message)
                @php
                    $user1 = $message->sender;
                    $user2 = $user1->id === $message->receiver_id ? $message->receiver : $message->receiver;
                    
                    // Fetch all messages in this thread for quick viewing (if needed)
                    // Note: For a live Admin tool, you might fetch the last 10 messages instead of just the latest one
                    $threadMessages = App\Models\Message::where(function($query) use ($user1, $user2) {
                        $query->where('sender_id', $user1->id)->where('receiver_id', $user2->id);
                    })->orWhere(function($query) use ($user1, $user2) {
                        $query->where('sender_id', $user2->id)->where('receiver_id', $user1->id);
                    })->orderBy('created_at', 'desc')->get();
                @endphp
                
                <div class="p-4 grid grid-cols-12 items-center hover:bg-gray-50 transition duration-150">
                    <!-- Participants -->
                    <div class="col-span-3 text-sm text-gray-900 font-medium">
                        {{ $user1->name }} ({{ $user1->id }}) <span class="text-gray-500">to</span> {{ $user2->name }} ({{ $user2->id }})
                        <p class="text-xs text-gray-600 mt-1 italic truncate">
                             Latest: {{ Str::limit($message->body, 50) }}
                        </p>
                    </div>

                    <!-- Roles -->
                    <div class="col-span-2 space-x-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ ucfirst($user1->roles->first()->name ?? 'N/A') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst($user2->roles->first()->name ?? 'N/A') }}
                        </span>
                    </div>

                    <!-- Property -->
                    <div class="col-span-4 text-sm text-gray-600">
                        @if ($message->property)
                            <a href="{{ route('landlord.properties.show', $message->property) }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $message->property->title }}
                            </a>
                        @else
                            N/A (General Inquiry)
                        @endif
                    </div>

                    <!-- Time -->
                    <div class="col-span-3 text-right text-sm text-gray-500">
                        {{ $message->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No conversations have been initiated yet.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
