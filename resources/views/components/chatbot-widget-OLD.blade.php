<!-- AI Chatbot Assistant - Version Home -->
<style>
    /* Animations personnalisées */
    @keyframes bounce-gentle {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
        
        <!-- Icône de message -->
        <svg id="message-icon" class="w-8 h-8 z-10 transform transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 2H4C2.9 2 2 2.9 2 4V16C2 17.1 2.9 18 4 18H18L22 22V4C22 2.9 21.1 2 20 2ZM20 17.17L18.83 16H4V4H20V17.17Z"/>
            <circle cx="8" cy="10" r="1.5"/>
            <circle cx="12" cy="10" r="1.5"/>
            <circle cx="16" cy="10" r="1.5"/>
        </svg>
        
        <!-- Icône de fermeture -->
        <svg id="close-icon" class="w-7 h-7 z-10 hidden transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        
        <!-- Effet de pulsation -->
        <div class="absolute inset-0 rounded-full bg-blue-400 opacity-25 animate-ping"></div>
    </button>

    <!-- Badge de notification avec animation -->
    <div id="notification-badge" class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 text-white text-xs font-bold rounded-full items-center justify-center animate-bounce shadow-lg" style="display: none;">
        <span id="notification-count">0</span>
    </div>

    <!-- Fenêtre de chat moderne -->
    <div id="chatbot-window" class="hidden absolute bottom-20 right-0 w-96 h-[500px] bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Header stylisé -->
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white p-4 relative overflow-hidden">
            <!-- Décoration de fond -->
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/10 to-transparent"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center">
                    <!-- Avatar animé -->
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mr-3 animate-pulse">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H11.81C11.42 22.34 11.17 21.6 11.08 20.84C9.89 20.4 9 19.3 9 18C9 16.34 10.34 15 12 15S15 16.34 15 18C15 18.79 14.71 19.51 14.22 20.06C14.32 20.71 14.53 21.33 14.84 21.9H19C20.11 21.9 21 21.01 21 19.9V9M18 14.5V13H16V14.5H18ZM18 12V10.5H16V12H18Z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg">Assistant Bélier</h4>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            <p class="text-sm opacity-90">En ligne</p>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex items-center space-x-2">
                    <button id="minimize-chat" class="text-white/80 hover:text-white hover:bg-white/20 rounded-full p-2 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Zone des messages avec design moderne -->
        <div id="chat-messages" class="flex-1 p-4 h-80 overflow-y-auto bg-gradient-to-b from-gray-50 to-white custom-scrollbar">
            <!-- Message de bienvenue amélioré -->
            <div class="mb-6 animate-fade-in">
                <div class="flex items-start space-x-3">
                    <!-- Avatar du bot avec animation -->
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-lg shadow-lg flex-shrink-0 animate-bounce">
                        🤖
                    </div>
                    <div class="flex-1">
                        <!-- Bulle de message stylisée -->
                        <div class="bg-white p-4 rounded-2xl rounded-tl-sm shadow-lg border border-gray-100 max-w-xs relative">
                            <!-- Flèche de la bulle -->
                            <div class="absolute -left-2 top-4 w-4 h-4 bg-white border-l border-t border-gray-100 transform -rotate-45"></div>
                            
                            <p class="text-sm text-gray-800 leading-relaxed">
                                <span class="font-semibold text-blue-600">Bonjour !</span> 👋<br>
                                Je suis votre assistant virtuel. Comment puis-je vous aider aujourd'hui ?
                            </p>
                            
                            <!-- Horodatage stylisé -->
                            <div class="flex items-center mt-2 space-x-1">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-gray-500 font-medium">Maintenant</span>
                            </div>
                        </div>
                        
                        <!-- Suggestions rapides -->
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full hover:bg-blue-200 transition-colors duration-200 quick-action" data-message="Bonjour">
                                👋 Saluer
                            </button>
                            <button class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full hover:bg-purple-200 transition-colors duration-200 quick-action" data-message="Aide">
                                ❓ Aide
                            </button>
                            <button class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full hover:bg-green-200 transition-colors duration-200 quick-action" data-message="Articles">
                                📰 Articles
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone de saisie moderne avec animations -->
        <div class="p-4 bg-white border-t border-gray-200 relative">
            <!-- Indicateur de frappe -->
            <div id="typing-indicator" class="hidden mb-2">
                <div class="flex items-center space-x-2 text-gray-500 text-sm">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                    <span>Assistant en train d'écrire...</span>
                </div>
            </div>
            
            <form id="chat-form" class="flex space-x-3 items-end">
                <!-- Zone de texte avec design moderne -->
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        id="chat-input" 
                        placeholder="Tapez votre message..." 
                        class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white text-sm transition-all duration-200 placeholder-gray-400"
                        maxlength="1000"
                        autocomplete="off"
                    >
                    <!-- Compteur de caractères -->
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-2">
                        <span id="char-count" class="text-xs text-gray-400">0/1000</span>
                    </div>
                </div>
                
                <!-- Bouton d'envoi avec design moderne -->
                <button 
                    type="submit" 
                    id="send-btn"
                    class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    disabled
                >
                    <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            
            <!-- Actions rapides en bas -->
            <div class="mt-3 flex items-center justify-between">
                <div class="flex space-x-2">
                    <button class="text-gray-400 hover:text-blue-500 transition-colors duration-200" title="Emoji">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-green-500 transition-colors duration-200" title="Fichier">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </button>
                </div>
                <div class="text-xs text-gray-400">
                    Appuyez sur Entrée pour envoyer
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ======== STYLES MODERNES POUR LE CHATBOT ======== */

/* Police et base */
#chatbot-widget {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    letter-spacing: -0.01em;
}

/* Animation d'apparition de la fenêtre */
#chatbot-window.show {
    display: block !important;
    opacity: 1;
    transform: scale(1);
    animation: chatAppear 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes chatAppear {
    0% {
        opacity: 0;
        transform: scale(0.8) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Animation fade-in pour les messages */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

/* Scrollbar personnalisée moderne */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 6px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #cbd5e1, #94a3b8);
    border-radius: 6px;
    transition: background 0.3s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #94a3b8, #64748b);
}

/* Animation de pulsation moderne */
@keyframes modernPulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.4;
    }
}

/* Effet de shadow moderne */
.shadow-3xl {
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25), 
                0 25px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Messages utilisateur et bot stylisés */
.user-message {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    padding: 12px 16px;
    border-radius: 20px 20px 4px 20px;
    max-width: 75%;
    margin-left: auto;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    animation: slideInRight 0.3s ease-out;
}

.bot-message {
    background: white;
    color: #374151;
    padding: 12px 16px;
    border-radius: 20px 20px 20px 4px;
    max-width: 75%;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideInLeft 0.3s ease-out;
}

.admin-message {
    background: linear-gradient(135deg, #10b981, #047857);
    color: white;
    padding: 12px 16px;
    border-radius: 20px 20px 20px 4px;
    max-width: 75%;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Position fixe renforcée */
#chatbot-widget {
    position: fixed !important;
    bottom: 1.5rem !important;
    right: 1.5rem !important;
    z-index: 9999 !important;
}

/* Responsive design */
@media (max-width: 768px) {
    #chatbot-widget {
        bottom: 1rem !important;
        right: 1rem !important;
    }
    
    #chatbot-window {
        width: 320px !important;
        height: 450px !important;
    }
}

.message-time {
    font-size: 11px;
    color: #6b7280;
    margin-top: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const chatToggle = document.getElementById('chatbot-toggle');
    const chatWindow = document.getElementById('chatbot-window');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const minimizeChat = document.getElementById('minimize-chat');
    const messageIcon = document.getElementById('message-icon');
    const closeIcon = document.getElementById('close-icon');
    const notificationBadge = document.getElementById('notification-badge');
    const notificationCount = document.getElementById('notification-count');
    const sendBtn = document.getElementById('send-btn');
    const charCount = document.getElementById('char-count');
    const typingIndicator = document.getElementById('typing-indicator');

    let isOpen = false;
    let messagePolling = null;

    // Gestion du compteur de caractères
    chatInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/1000`;
        
        // Activer/désactiver le bouton d'envoi
        sendBtn.disabled = length === 0;
        
        // Changer la couleur selon la limite
        if (length > 900) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
    });

    // Gestion des actions rapides
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quick-action')) {
            const message = e.target.getAttribute('data-message');
            chatInput.value = message;
            chatInput.focus();
            sendBtn.disabled = false;
        }
    });

    // Toggle du chat avec animations
    chatToggle.addEventListener('click', function() {
        isOpen = !isOpen;
        if (isOpen) {
            openChat();
        } else {
            closeChat();
        }
    });

    function openChat() {
        chatWindow.classList.remove('hidden');
        chatWindow.classList.add('show');
        messageIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        
        // Focus automatique sur l'input
        setTimeout(() => {
            chatInput.focus();
        }, 300);
        
        // Masquer les notifications
        notificationBadge.classList.add('hidden');
        
        // Charger les messages
        loadMessages();
        
        // Démarrer le polling des messages
        startMessagePolling();
    }

    function closeChat() {
        chatWindow.classList.remove('show');
        setTimeout(() => {
            chatWindow.classList.add('hidden');
        }, 300);
        messageIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        
        // Arrêter le polling
        stopMessagePolling();
    }

    // Minimiser le chat
    minimizeChat.addEventListener('click', function(e) {
        e.stopPropagation();
        closeChat();
        isOpen = false;
    });

    // Envoi de message avec améliorations
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (message && !sendBtn.disabled) {
            sendMessage(message);
            chatInput.value = '';
            charCount.textContent = '0/1000';
            sendBtn.disabled = true;
        }
    });

    // Envoi avec Entrée (Shift+Entrée pour nouvelle ligne)
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Fonction pour envoyer un message
    function sendMessage(message) {
        // Afficher le message de l'utilisateur immédiatement
        addMessage(message, 'user');
        
        // Afficher l'indicateur de frappe
        showTypingIndicator();
        
        // Désactiver temporairement l'envoi
        sendBtn.disabled = true;
        
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
            hideTypingIndicator();
            if (data.success) {
                // La réponse sera chargée via le polling
                setTimeout(loadMessages, 500);
            } else {
                addMessage('Désolé, une erreur s\'est produite. Veuillez réessayer.', 'bot');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            console.error('Erreur:', error);
            addMessage('Erreur de connexion. Veuillez vérifier votre connexion internet.', 'bot');
        })
        .finally(() => {
            sendBtn.disabled = chatInput.value.trim() === '';
        });
    }

    // Afficher l'indicateur de frappe
    function showTypingIndicator() {
        typingIndicator.classList.remove('hidden');
        scrollToBottom();
    }

    // Masquer l'indicateur de frappe
    function hideTypingIndicator() {
        typingIndicator.classList.add('hidden');
    }

    // Ajouter un message à l'interface
    function addMessage(content, type, timestamp = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-4 animate-fade-in';
        
        const time = timestamp || 'À l\'instant';
        const isUser = type === 'user';
        
        messageDiv.innerHTML = `
            <div class="flex items-start ${isUser ? 'justify-end' : ''} space-x-3">
                ${!isUser ? `
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    🤖
                </div>
                ` : ''}
                <div class="flex-1 ${isUser ? 'flex justify-end' : ''}">
                    <div class="${isUser ? 'user-message' : 'bot-message'}">
                        <p class="text-sm leading-relaxed">${content}</p>
                        <div class="flex items-center mt-2 space-x-1 ${isUser ? 'justify-end' : ''}">
                            <svg class="w-3 h-3 ${isUser ? 'text-white/70' : 'text-gray-400'}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs ${isUser ? 'text-white/70' : 'text-gray-500'} font-medium">${time}</span>
                        </div>
                    </div>
                </div>
                ${isUser ? `
                <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    👤
                </div>
                ` : ''}
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Faire défiler vers le bas
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    // Charger les messages depuis le serveur
    function loadMessages() {
        fetch('/chatbot/messages')
            .then(response => response.json())
            .then(data => {
                if (data.messages) {
                    // Effacer les anciens messages (sauf le message de bienvenue)
                    const welcomeMessage = chatMessages.querySelector('.animate-fade-in');
                    chatMessages.innerHTML = '';
                    if (welcomeMessage) {
                        chatMessages.appendChild(welcomeMessage);
                    }
                    
                    // Ajouter les nouveaux messages
                    data.messages.forEach(msg => {
                        const type = msg.type === 'user' ? 'user' : 'bot';
                        const time = new Date(msg.created_at).toLocaleTimeString('fr-FR', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        addMessage(msg.message, type, time);
                    });
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des messages:', error);
            });
    }

    // Démarrer le polling des messages
    function startMessagePolling() {
        messagePolling = setInterval(loadMessages, 3000); // Vérifie toutes les 3 secondes
    }

    // Arrêter le polling des messages
    function stopMessagePolling() {
        if (messagePolling) {
            clearInterval(messagePolling);
            messagePolling = null;
        }
    }

    // Gestion de la notification de nouveaux messages
    function updateNotificationBadge(count) {
        if (count > 0 && !isOpen) {
            notificationCount.textContent = count > 99 ? '99+' : count;
            notificationBadge.classList.remove('hidden');
        } else {
            notificationBadge.classList.add('hidden');
        }
    }

    // Vérifier les nouveaux messages périodiquement (même quand fermé)
    setInterval(() => {
        if (!isOpen) {
            fetch('/chatbot/messages')
                .then(response => response.json())
                .then(data => {
                    if (data.unread_count) {
                        updateNotificationBadge(data.unread_count);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la vérification des notifications:', error);
                });
        }
    }, 10000); // Vérifie toutes les 10 secondes

    // Gérer la fermeture en cliquant à l'extérieur
    document.addEventListener('click', function(e) {
        if (isOpen && !chatWindow.contains(e.target) && !chatToggle.contains(e.target)) {
            closeChat();
            isOpen = false;
        }
    });

    // Empêcher la fermeture lors du clic dans la fenêtre
    chatWindow.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Animation au survol du bouton principal
    chatToggle.addEventListener('mouseenter', function() {
        if (!isOpen) {
            this.style.transform = 'scale(1.1)';
        }
    });

    chatToggle.addEventListener('mouseleave', function() {
        if (!isOpen) {
            this.style.transform = 'scale(1)';
        }
    });

    // Initialisation
    console.log('🤖 Chatbot widget initialisé avec succès !');
});
</script>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white text-sm mr-3 flex-shrink-0">
                        🤖
                    </div>
                    <div class="admin-message p-3 rounded-lg shadow-sm max-w-xs">
                        <p class="text-sm text-gray-800">${message}</p>
                        <span class="message-time block">${time}</span>
                    </div>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Charger les messages existants
    function loadMessages() {
        fetch('/chatbot/messages')
        .then(response => response.json())
        .then(messages => {
            // Vider les messages existants sauf le message de bienvenue
            const welcomeMessage = chatMessages.querySelector('.mb-4');
            chatMessages.innerHTML = '';
            chatMessages.appendChild(welcomeMessage);

            // Ajouter les messages
            messages.forEach(msg => {
                addMessageToChat(msg.message, msg.type);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des messages:', error);
        });
    }

    // Vérifier les nouveaux messages périodiquement
    setInterval(() => {
        if (!isOpen) {
            // Vérifier s'il y a de nouveaux messages
            fetch('/chatbot/messages')
            .then(response => response.json())
            .then(messages => {
                // Logique pour afficher le badge de notification si nécessaire
                const adminMessages = messages.filter(msg => msg.type === 'admin' && !msg.read_at);
                if (adminMessages.length > 0) {
                    notificationBadge.textContent = adminMessages.length;
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la vérification des messages:', error);
            });
        }
    }, 30000); // Vérifier toutes les 30 secondes
});
</script>
