@extends('layouts.app')

@section('title', 'Assistant Virtuel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-yellow-50 to-red-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center justify-center">
                    <span class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-4">
                        🤖
                    </span>
                    Assistant Virtuel Bélier
                </h1>
                <p class="text-gray-600 mt-2">Posez vos questions, nous sommes là pour vous aider !</p>
            </div>

            <!-- Zone de chat principale -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header du chat -->
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                            👋
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">Conversation avec l'équipe</h2>
                            <p class="text-orange-100">En ligne - Réponse sous 24h</p>
                        </div>
                    </div>
                </div>

                <!-- Zone des messages -->
                <div id="main-chat-messages" class="h-96 overflow-y-auto p-6 bg-gray-50">
                    <!-- Message de bienvenue -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white text-sm mr-4 flex-shrink-0">
                                🤖
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm max-w-md">
                                <p class="text-gray-800">Bonjour ! Je suis l'assistant virtuel du Bélier Intrépide. Comment puis-je vous aider aujourd'hui ?</p>
                                <span class="text-xs text-gray-500 mt-2 block">Maintenant</span>
                            </div>
                        </div>
                    </div>

                    <!-- Messages existants -->
                    @foreach($messages as $message)
                        <div class="mb-6">
                            @if($message->type === 'user')
                                <div class="flex items-start justify-end">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white p-4 rounded-lg shadow-sm max-w-md">
                                        <p class="text-sm">{{ $message->message }}</p>
                                        <span class="text-xs opacity-80 mt-2 block">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm ml-4 flex-shrink-0">
                                        👤
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start">
                                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white text-sm mr-4 flex-shrink-0">
                                        🤖
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-sm max-w-md">
                                        <p class="text-sm text-gray-800">{{ $message->message }}</p>
                                        <span class="text-xs text-gray-500 mt-2 block">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Zone de saisie -->
                <div class="p-6 bg-white border-t border-gray-200">
                    <form id="main-chat-form" class="flex space-x-4">
                        @csrf
                        <input
                            type="text"
                            id="main-chat-input"
                            placeholder="Tapez votre message..."
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            maxlength="1000"
                        >
                        <button
                            type="submit"
                            class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center space-x-2"
                        >
                            <span>Envoyer</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Vos conversations sont privées et sécurisées
                        </p>
                    </div>
                </div>
            </div>

            <!-- Suggestions rapides -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Questions fréquentes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <button onclick="sendQuickMessage('Comment m\'abonner à votre journal ?')"
                            class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow text-left border border-gray-100">
                        <div class="flex items-center mb-2">
                            <span class="text-orange-500 mr-2">📰</span>
                            <span class="font-medium text-gray-800">Abonnement</span>
                        </div>
                        <p class="text-sm text-gray-600">Comment m'abonner à votre journal ?</p>
                    </button>

                    <button onclick="sendQuickMessage('Quels sont vos tarifs ?')"
                            class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow text-left border border-gray-100">
                        <div class="flex items-center mb-2">
                            <span class="text-orange-500 mr-2">💰</span>
                            <span class="font-medium text-gray-800">Tarifs</span>
                        </div>
                        <p class="text-sm text-gray-600">Quels sont vos tarifs ?</p>
                    </button>

                    <button onclick="sendQuickMessage('Comment contacter la rédaction ?')"
                            class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow text-left border border-gray-100">
                        <div class="flex items-center mb-2">
                            <span class="text-orange-500 mr-2">📧</span>
                            <span class="font-medium text-gray-800">Contact</span>
                        </div>
                        <p class="text-sm text-gray-600">Comment contacter la rédaction ?</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#main-chat-messages::-webkit-scrollbar {
    width: 6px;
}

#main-chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#main-chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('main-chat-form');
    const chatInput = document.getElementById('main-chat-input');
    const chatMessages = document.getElementById('main-chat-messages');

    // Scroll vers le bas au chargement
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Envoi de message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (message) {
            sendMessage(message);
            chatInput.value = '';
        }
    });

    function sendMessage(message) {
        // Ajouter le message à l'interface
        addMessageToChat(message, 'user');

        // Envoyer au serveur
        fetch('/chatbot/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                setTimeout(() => {
                    addMessageToChat('Merci pour votre message ! Un membre de notre équipe vous répondra bientôt.', 'admin');
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            addMessageToChat('Erreur lors de l\'envoi du message. Veuillez réessayer.', 'admin');
        });
    }

    function addMessageToChat(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-6';

        const time = new Date().toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });

        if (type === 'user') {
            messageDiv.innerHTML = `
                <div class="flex items-start justify-end">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white p-4 rounded-lg shadow-sm max-w-md">
                        <p class="text-sm">${message}</p>
                        <span class="text-xs opacity-80 mt-2 block">${time}</span>
                    </div>
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm ml-4 flex-shrink-0">
                        👤
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white text-sm mr-4 flex-shrink-0">
                        🤖
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm max-w-md">
                        <p class="text-sm text-gray-800">${message}</p>
                        <span class="text-xs text-gray-500 mt-2 block">${time}</span>
                    </div>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Fonction pour les suggestions rapides
    window.sendQuickMessage = function(message) {
        sendMessage(message);
    };
});
</script>
@endsection
