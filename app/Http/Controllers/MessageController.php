<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Message;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    class MessageController extends Controller
    {

        public function messagesApp($otherUserId = null)
        {
            $currentUserId = Auth::id();

            // 1. Fetch all unique conversation threads for the sidebar
            $threads = Message::where('sender_id', $currentUserId)
                                ->orWhere('receiver_id', $currentUserId)
                                ->orderBy('created_at', 'desc')
                                ->get()
                                ->unique(function ($item) use ($currentUserId) {
                                    // Group by the "other" user ID in the conversation
                                    return $item->sender_id == $currentUserId ? $item->receiver_id : $item->sender_id;
                                });

            $activeChat = null;
            $messages = collect();
            $otherUser = null;

            if ($otherUserId) {
                $otherUser = User::find($otherUserId);

                // 2. Fetch the active conversation history
                $messages = Message::where(function($query) use ($currentUserId, $otherUserId) {
                    $query->where('sender_id', $currentUserId)->where('receiver_id', $otherUserId);
                })->orWhere(function($query) use ($currentUserId, $otherUserId) {
                    $query->where('sender_id', $otherUserId)->where('receiver_id', $currentUserId);
                })->orderBy('created_at', 'asc')->get();

                // 3. Mark received messages in the active thread as read
                Message::where('receiver_id', $currentUserId)
                    ->where('sender_id', $otherUserId)
                    ->update(['is_read' => true]);

                $activeChat = [
                    'user' => $otherUser,
                    'messages' => $messages,
                ];
            }

            return view('messages.app', compact('threads', 'activeChat', 'otherUserId'));
        }

        public function store(Request $request)
        {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'body' => 'required|string|max:1000',
            ]);

            $message = Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'body' => $request->body,
                'property_id' => $request->property_id ?? null, // Allows linking to a property
            ]);

            // Redirect back to the active conversation thread
            $rolePrefix = Auth::user()->roles->first()->name; // 'landlord' or 'tenant'
            return redirect()->route("$rolePrefix.messages.app", ['otherUserId' => $request->receiver_id]);
        }

        /**
         * Display the admin monitoring view (read-only list of all threads).
         */
        public function adminIndex()
        {
            // Get the latest message for every unique (sender_id, receiver_id) pair
            $allMessages = Message::with(['sender', 'receiver', 'property'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Group messages into distinct threads based on the two users involved
            $threads = $allMessages->unique(function ($item) {
                // Create a canonical key for the thread (e.g., "1_5" not "5_1")
                return min($item->sender_id, $item->receiver_id) . '_' . max($item->sender_id, $item->receiver_id);
            });

            return view('admin.messages.index', compact('threads'));
        }
    }
