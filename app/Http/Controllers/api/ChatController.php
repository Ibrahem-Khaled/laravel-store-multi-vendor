<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Get all conversations for the authenticated user.
     */
    public function getConversations(Request $request)
    {
        $user = auth()->guard('api')->user();

        $conversations = $user->conversations()
            ->with([
                'participants:id,name',
                'messages' => fn($query) => $query->latest()->limit(1)
            ])
            ->latest('updated_at')
            ->get();

        return ConversationResource::collection($conversations);
    }

    /**
     * Get messages for a specific conversation (for initial load).
     */
    public function getMessages(Request $request, Conversation $conversation)
    {
        Gate::authorize('view-conversation', $conversation);

        $messages = $conversation->messages()
            ->with('user:id,name')
            ->latest()
            ->paginate(25);

        return MessageResource::collection($messages);
    }

    /**
     * The core function for the Polling system.
     * Fetches new messages since the last known message.
     */
    public function getNewMessages(Request $request, Conversation $conversation)
    {
        Gate::authorize('view-conversation', $conversation);

        $request->validate(['last_message_id' => 'sometimes|integer|exists:messages,id']);

        $query = $conversation->messages()->with('user:id,name');

        if ($request->filled('last_message_id')) {
            $query->where('id', '>', $request->last_message_id);
        }

        $newMessages = $query->orderBy('id', 'asc')->get();

        return MessageResource::collection($newMessages);
    }


    /**
     * Send a new message (without broadcasting).
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        // Gate::authorize('view-conversation', $conversation);

        // 3. التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'body' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,png,jpeg,mp3,ogg,m4a,wav,mp4,mov,webm|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $attachmentUrl = null;
        $messageType = 'text';

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat_attachments', 'public');
            $attachmentUrl = $path;
            $mime = $request->file('attachment')->getClientMimeType();

            if (str_starts_with($mime, 'image/')) $messageType = 'image';
            elseif (str_starts_with($mime, 'audio/')) $messageType = 'voice';
            elseif (str_starts_with($mime, 'video/')) $messageType = 'video';
        }

        if (empty($validated['body']) && !$attachmentUrl) {
            return response()->json(['message' => 'Cannot send an empty message.'], 422);
        }

        $message = $conversation->messages()->create([
            'user_id' => auth()->guard('api')->user()->id,
            'body' => $validated['body'] ?? null,
            'attachment_url' => $attachmentUrl,
            'type' => $messageType,
        ]);

        $conversation->touch();
        $message->load('user:id,name');

        // لا يوجد بث هنا، فقط نرجع الرسالة التي تم إنشاؤها
        return new MessageResource($message);
    }

    /**
     * Start a new conversation with another user.
     */
    public function startConversation(Request $request)
    {
        $validated = $request->validate(['user_id' => 'required|integer|exists:users,id']);

        $currentUser = auth()->guard('api')->user();

        if ($currentUser->id == $validated['user_id']) {
            return response()->json(['message' => 'You cannot start a conversation with yourself.'], 422);
        }

        // ابحث إذا كانت هناك محادثة حالية بين هذين المستخدمين
        $conversation = $currentUser->conversations()
            ->whereHas('participants', function ($query) use ($validated) {
                $query->where('user_id', $validated['user_id']);
            })
            ->first();

        if ($conversation) {
            return new ConversationResource($conversation->load('participants', 'messages'));
        }

        $newConversation = Conversation::create();
        $newConversation->participants()->attach([$currentUser->id, $validated['user_id']]);

        return new ConversationResource($newConversation);
    }
}
