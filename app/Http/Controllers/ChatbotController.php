<?php

namespace App\Http\Controllers;

use App\Models\ChatbotMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function index()
    {
        // Déterminer l'ID utilisateur (connecté ou invité)
        $userId = Auth::id();
        if (!$userId) {
            $userId = session('guest_user_id');
        }

        $messages = ChatbotMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chatbot.index', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Si l'utilisateur n'est pas connecté, créer une session temporaire
        $userId = Auth::id();
        if (!$userId) {
            // Générer un ID de session unique pour les utilisateurs non connectés
            $sessionId = session()->getId();
            if (!session()->has('guest_user_id')) {
                session()->put('guest_user_id', 'guest_' . time() . '_' . substr($sessionId, 0, 8));
            }
            $userId = session('guest_user_id');
        }

        $message = ChatbotMessage::create([
            'user_id' => $userId,
            'message' => $request->message,
            'type' => 'user',
            'metadata' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_guest' => !Auth::check(),
                'session_id' => session()->getId()
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function getMessages()
    {
        // Déterminer l'ID utilisateur (connecté ou invité)
        $userId = Auth::id();
        if (!$userId) {
            $userId = session('guest_user_id');
        }

        $messages = ChatbotMessage::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Marquer les messages comme lus (pour l'admin)
     */
    public function markAsRead(Request $request)
    {
        $messageIds = $request->input('message_ids', []);
        
        ChatbotMessage::whereIn('id', $messageIds)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Répondre à un message (pour l'admin)
     */
    public function replyMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'reply_to' => 'nullable|exists:chatbot_messages,id'
        ]);

        $message = ChatbotMessage::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type' => 'admin',
            'metadata' => [
                'admin_id' => Auth::id(),
                'reply_to' => $request->reply_to
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('user')
        ]);
    }

    /**
     * Obtenir les conversations groupées par utilisateur (pour l'admin)
     */
    public function getConversations()
    {
        $conversations = ChatbotMessage::with('user')
            ->selectRaw('user_id, COUNT(*) as message_count, MAX(created_at) as last_message, 
                        SUM(CASE WHEN read_at IS NULL AND type = "user" THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('user_id')
            ->orderBy('last_message', 'desc')
            ->get();

        return response()->json($conversations);
    }

    /**
     * Obtenir les messages d'une conversation spécifique (pour l'admin)
     */
    public function getConversation($userId)
    {
        $messages = ChatbotMessage::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
