<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-xl sm:text-2xl font-bold text-gray-800 truncate">Mon Site</a>
                </div>

                <!-- Navigation Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/articles" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Articles</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Connexion</a>
                    <a href="/register" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">Inscription</a>
                </div>

                <!-- Menu Mobile Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu Mobile -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 pb-4">
                <div class="pt-4 space-y-2">
                    <a href="/articles" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors duration-200">Articles</a>
                    <a href="/login" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors duration-200">Connexion</a>
                    <a href="/register" class="block px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-200 text-center">Inscription</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto mt-6 sm:mt-10 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">Bienvenue sur notre site</h1>
            <p class="text-lg sm:text-xl text-gray-600 mb-6 sm:mb-8 max-w-3xl mx-auto">Découvrez nos derniers articles et rejoignez notre communauté.</p>
            <a href="/articles" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 inline-block w-full sm:w-auto transition-all duration-200 font-semibold">
                Voir les articles
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-12 sm:mt-16">
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold">Articles récents</h2>
                </div>
                <p class="text-gray-600">Découvrez nos derniers articles publiés sur les sujets qui vous intéressent.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold">Communauté</h2>
                </div>
                <p class="text-gray-600">Rejoignez une communauté active et passionnée de lecteurs et contributeurs.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h10a2 2 0 012 2v2"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold">Ressources</h2>
                </div>
                <p class="text-gray-600">Accédez à nos ressources exclusives et outils pratiques.</p>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white mt-16 sm:mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-sm sm:text-base">&copy; {{ date('Y') }} Mon Site. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Widget Chatbot -->
    <div id="chatbot-widget" class="fixed bottom-4 right-4 z-50">
        <!-- Bouton d'ouverture -->
        <button id="chat-toggle" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
        </button>

        <!-- Fenêtre de chat responsive -->
        <div id="chat-window" class="bg-white rounded-2xl shadow-2xl w-full sm:w-80 h-96 mb-4 overflow-hidden border border-gray-200 fixed sm:relative bottom-20 sm:bottom-0 left-4 right-4 sm:left-auto sm:right-auto" style="display: none; flex-direction: column;">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <span class="text-sm">🤖</span>
                    </div>
                    <div>
                        <h3 class="font-semibold">Assistant</h3>
                        <span class="text-xs opacity-90">En ligne</span>
                    </div>
                </div>
                <button id="chat-close" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Messages -->
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50">
                <div class="flex items-start gap-2 mb-4">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                    <div class="bg-white p-3 rounded-lg shadow-sm max-w-xs">
                        <p class="text-sm text-gray-800">Bonjour ! Comment puis-je vous aider aujourd'hui ?</p>
                    </div>
                </div>
            </div>

            <!-- Input -->
            <div class="p-4 bg-white border-t border-gray-200">
                <form id="chat-form" class="flex gap-2">
                    <input type="text" id="chat-input" placeholder="Tapez votre message..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Chatbot -->
    <script>
        // Variables globales
        const chatToggle = document.getElementById('chat-toggle');
        const chatWindow = document.getElementById('chat-window');
        const chatClose = document.getElementById('chat-close');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');
        
        // Toggle chat window
        chatToggle.addEventListener('click', () => {
            chatWindow.style.display = 'flex';
            chatWindow.classList.remove('hidden');
            chatInput.focus();
        });
        
        chatClose.addEventListener('click', () => {
            chatWindow.style.display = 'none';
            chatWindow.classList.add('hidden');
        });
        
        // Send message
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessage(message, 'user');
            chatInput.value = '';
            
            try {
                // Send to server
                const response = await fetch('/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ message })
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Simulate bot response
                    setTimeout(() => {
                        addMessage('Merci pour votre message ! Un administrateur vous répondra bientôt.', 'bot');
                    }, 1000);
                }
            } catch (error) {
                console.error('Erreur:', error);
                addMessage('Erreur de connexion. Veuillez réessayer.', 'bot');
            }
        });
        
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex items-start gap-2 mb-4 ${sender === 'user' ? 'justify-end' : ''}`;
            
            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="bg-green-500 text-white p-3 rounded-lg shadow-sm max-w-xs">
                        <p class="text-sm">${text}</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white text-sm">👤</div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                    <div class="bg-white p-3 rounded-lg shadow-sm max-w-xs">
                        <p class="text-sm text-gray-800">${text}</p>
                    </div>
                `;
            }
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>

    <!-- Meta CSRF Token pour les requêtes AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @vite('resources/js/app.js')
</body>
</html>
