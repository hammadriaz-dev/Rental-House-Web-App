<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex h-[80vh]">

                {{-- 1. Conversation Sidebar (Left Panel) --}}
                <div class="w-1/3 border-r border-gray-200 bg-gray-50 flex-shrink-0">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Your Conversations</h2>
                    </div>

                    <div class="h-full overflow-y-auto">
                        @forelse ($threads as $message)
                            {{-- Determine the 'other' user in the conversation --}}
                            @php
                                $otherUser = ($message->sender_id == Auth::id()) ? $message->receiver : $message->sender;
                                $isActive = $otherUser->id == ($activeChat['user']->id ?? null);
                            @endphp

                            <a href="{{ route(Auth::user()->roles->first()->name . '.messages.app', $otherUser->id) }}"
                               class="block p-4 border-b border-gray-200 hover:bg-indigo-50 transition duration-150 {{ $isActive ? 'bg-indigo-100 border-l-4 border-indigo-600' : '' }}">

                                <p class="font-semibold text-gray-900 truncate">{{ $otherUser->name }} ({{ ucfirst($otherUser->role) }})</p>
                                <p class="text-sm text-gray-500 truncate">{{ $message->body }}</p>
                            </a>
                        @empty
                            <div class="p-4 text-center text-gray-500">
                                You have no active conversations.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- 2. Active Chat Window (Right Panel) --}}
                <div class="flex-grow flex flex-col">
                    @if ($activeChat)
                        {{-- Chat Header --}}
                        <div class="p-4 border-b border-gray-200 bg-white">
                            <h3 class="text-lg font-bold text-gray-800">Chatting with {{ $activeChat['user']->name }}</h3>
                        </div>

                        {{-- Message History --}}
                        <div class="flex-grow p-4 overflow-y-auto space-y-4 bg-gray-100" id="chat-messages">
                            @foreach ($activeChat['messages'] as $message)
                                <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg
                                                {{ $message->sender_id == Auth::id() ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-800 rounded-tl-none shadow' }}">
                                        <p class="text-sm">{{ $message->body }}</p>
                                        <span class="block text-xs mt-1 {{ $message->sender_id == Auth::id() ? 'text-indigo-200' : 'text-gray-400' }} text-right">
                                            {{ $message->created_at->format('g:i A') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Message Input --}}
                        <div class="p-4 bg-white border-t border-gray-200">
                            <form action="{{ route(Auth::user()->roles->first()->name . '.messages.store') }}" method="POST" class="flex">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $activeChat['user']->id }}">

                                <input type="text" name="body" placeholder="Type your message..." required
                                       class="flex-grow rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />

                                <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-r-md hover:bg-indigo-700 transition">
                                    Send
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Initial State --}}
                        <div class="flex-grow flex items-center justify-center p-8 bg-gray-50 text-center text-gray-500">
                            <p class="text-lg">Select a conversation from the left to view the chat history, or start a new conversation.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Simple JS to ensure the chat window scrolls to the bottom on load
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
