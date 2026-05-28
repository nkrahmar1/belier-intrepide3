@extends('layouts.admin')

@section('title', 'Messages')

@push('styles')
<style>
    .message-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .message-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .unread { border-left: 4px solid #3b82f6; background-color: #eff6ff; }
    .read { border-left: 4px solid #e5e7eb; }
    .conversation-card:hover {
        background-color: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-100 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <span class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                    ✉️
                </span>
                Gestion des Messages
            </h1>
            <p class="text-gray-600 mt-2">Gérez toutes les conversations et messages utilisateurs</p>
        </div>
        <button class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Nouveau Message
        </button>
    </div>

    <!-- Statistiques des messages -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Messages Non Lus</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $unreadCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-blue-600 text-xl">📧</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Total Messages</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMessages ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-green-600 text-xl">💬</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-orange-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">Conversations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ isset($conversations) ? $conversations->count() : 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-orange-600 text-xl">🤖</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Conversations du Chatbot -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-orange-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        🤖
                    </span>
                    Conversations Chatbot
                </h3>
            </div>

            <div class="p-6">
                @if(isset($conversations) && $conversations->count() > 0)
                    <div class="space-y-4">
                        @foreach($conversations as $conversation)
                            <div class="conversation-card message-card {{ $conversation->unread_count > 0 ? 'unread' : 'read' }} rounded-xl p-4 cursor-pointer"
                                 onclick="openConversation({{ $conversation->user_id }})">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                            @if(str_starts_with($conversation->user_id, 'guest_'))
                                                👤
                                            @else
                                                {{ substr($conversation->user->name ?? 'U', 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            @if(str_starts_with($conversation->user_id, 'guest_'))
                                                <h4 class="font-semibold text-gray-800">Invité #{{ substr($conversation->user_id, -4) }}</h4>
                                                <p class="text-sm text-gray-600">Utilisateur non connecté</p>
                                            @else
                                                <h4 class="font-semibold text-gray-800">{{ $conversation->user->name ?? 'Utilisateur' }}</h4>
                                                <p class="text-sm text-gray-600">{{ $conversation->user->email ?? 'email@example.com' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($conversation->unread_count > 0)
                                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($conversation->last_message)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">{{ $conversation->message_count }} message(s)</p>
                                <div class="flex space-x-2 mt-3">
                                    <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-1 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Voir
                                    </button>
                                    <button class="bg-green-100 hover:bg-green-200 text-green-600 px-3 py-1 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-reply mr-1"></i>Répondre
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400">
                            <i class="fas fa-robot text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold mb-2">Aucune conversation</h3>
                            <p class="text-gray-500 mb-6">Aucun utilisateur n'a encore utilisé le chatbot</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Messages traditionnels -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                        📨
                    </span>
                    Messages Traditionnels
                </h3>
            </div>

            <div class="p-6">
                @if(isset($messages) && $messages->count() > 0)
                    <div class="space-y-4">
                        @foreach($messages as $message)
                            <div class="message-card read rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-red-400 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                            U
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Utilisateur</h4>
                                            <p class="text-sm text-gray-600">user@example.com</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <h5 class="font-medium text-gray-800 mb-2">Message de test</h5>
                                <p class="text-gray-600 text-sm">Ceci est un message de demonstration.</p>
                                <div class="flex space-x-2 mt-3">
                                    <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-1 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Lire
                                    </button>
                                    <button class="bg-green-100 hover:bg-green-200 text-green-600 px-3 py-1 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-reply mr-1"></i>Répondre
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400">
                            <i class="fas fa-envelope-open text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold mb-2">Aucun message traditionnel</h3>
                            <p class="text-gray-500 mb-6">Vous n'avez reçu aucun message traditionnel</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de conversation -->
    <div id="conversation-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-96">
                <div class="flex h-full">
                    <div class="flex-1 flex flex-col">
                        <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold" id="conversation-title">Conversation</h3>
                                <button onclick="closeConversation()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div id="conversation-messages" class="flex-1 p-6 overflow-y-auto bg-gray-50">
                            <!-- Messages chargés dynamiquement -->
                        </div>

                        <div class="p-4 bg-white border-t rounded-b-2xl">
                            <form id="reply-form" class="flex space-x-2">
                                <input type="hidden" id="reply-user-id">
                                <input type="text" id="reply-message" placeholder="Tapez votre réponse..."
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <button type="submit" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-2 rounded-lg hover:opacity-90">
                                    Envoyer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openConversation(userId) {
    document.getElementById('reply-user-id').value = userId;
    document.getElementById('conversation-modal').classList.remove('hidden');

    // Charger les messages de la conversation
    fetch(`/admin/chatbot/conversation/${userId}`)
        .then(response => response.json())
        .then(messages => {
            const container = document.getElementById('conversation-messages');
            container.innerHTML = '';

            // Mettre à jour le titre de la conversation
            const isGuest = userId.toString().startsWith('guest_');
            const title = isGuest ? `Invité #${userId.slice(-4)}` : 'Conversation';
            document.getElementById('conversation-title').textContent = title;

            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'mb-4';

                const time = new Date(message.created_at).toLocaleString('fr-FR');

                if (message.type === 'user') {
                    messageDiv.innerHTML = `
                        <div class="flex justify-end">
                            <div class="bg-blue-100 text-blue-800 p-3 rounded-lg max-w-xs">
                                <p class="text-sm">${message.message}</p>
                                <span class="text-xs text-blue-600 mt-1 block">${time}</span>
                            </div>
                        </div>
                    `;
                } else {
                    messageDiv.innerHTML = `
                        <div class="flex justify-start">
                            <div class="bg-green-100 text-green-800 p-3 rounded-lg max-w-xs">
                                <p class="text-sm">${message.message}</p>
                                <span class="text-xs text-green-600 mt-1 block">Admin - ${time}</span>
                            </div>
                        </div>
                    `;
                }

                container.appendChild(messageDiv);
            });

            container.scrollTop = container.scrollHeight;
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function closeConversation() {
    document.getElementById('conversation-modal').classList.add('hidden');
}

document.getElementById('reply-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const userId = document.getElementById('reply-user-id').value;
    const message = document.getElementById('reply-message').value.trim();

    if (!message) return;

    fetch('/admin/chatbot/reply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            user_id: userId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('reply-message').value = '';
            openConversation(userId);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});
</script>
@endsection
