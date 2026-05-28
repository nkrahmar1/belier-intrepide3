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

    .chatbot-bubble {
        animation: bounce-gentle 3s ease-in-out infinite;
    }

    .chatbot-message {
        animation: fadeInUp 0.3s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .typing-indicator span {
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-10px);
        }
    }
</style>

<!-- Chatbot avec Alpine.js -->
<div x-data="homeChatbotManager()" 
     x-init="init()"
     class="fixed bottom-6 right-6 z-50"
     style="z-index: 9999;">
    
    <!-- Chatbot Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="mb-4 w-96 h-[600px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="text-2xl">🐏</span>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></span>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Assistant Bélier</h3>
                    <p class="text-xs text-white/80">En ligne • Prêt à vous aider</p>
                </div>
            </div>
            <button @click="toggleChat()" 
                    class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Container -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900"
             x-ref="messagesContainer"
             @scroll.debounce="handleScroll()">
            
            <!-- Welcome Message -->
            <div x-show="messages.length === 0" class="text-center py-8">
                <span class="text-6xl mb-4 block">👋</span>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    Bonjour ! Je suis votre Assistant Bélier Intrépide
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Je peux vous aider avec :
                </p>
                <div class="space-y-2 text-sm text-left max-w-xs mx-auto">
                    <button @click="sendQuickCommand('articles')" 
                            class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                        📰 Découvrir les articles
                    </button>
                    <button @click="sendQuickCommand('subscription')" 
                            class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                        💎 S'abonner à la newsletter
                    </button>
                    <button @click="sendQuickCommand('services')" 
                            class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                        🛡️ Nos services
                    </button>
                    <button @click="sendQuickCommand('help')" 
                            class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-green-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                        ❓ Obtenir de l'aide
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <template x-for="(message, index) in messages" :key="index">
                <div :class="message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'"
                     class="chatbot-message">
                    <div :class="message.sender === 'user' 
                                  ? 'bg-green-600 text-white' 
                                  : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700'"
                         class="max-w-[80%] rounded-2xl px-4 py-3 shadow-sm">
                        <p class="text-sm whitespace-pre-wrap" x-html="message.text"></p>
                        <span class="text-xs opacity-70 mt-1 block" x-text="message.time"></span>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex justify-start chatbot-message">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3 shadow-sm">
                    <div class="typing-indicator flex gap-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="sendMessage()" class="flex gap-2">
                <input type="text" 
                       x-model="inputMessage"
                       :disabled="isTyping"
                       placeholder="Tapez votre message..."
                       class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 
                              bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white
                              focus:ring-2 focus:ring-green-500 focus:border-transparent
                              disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                <button type="submit" 
                        :disabled="!inputMessage.trim() || isTyping"
                        class="bg-green-600 text-white p-3 rounded-xl hover:bg-green-700 
                               disabled:opacity-50 disabled:cursor-not-allowed
                               transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                Propulsé par AI • Réponses intelligentes
            </p>
        </div>
    </div>

    <!-- Floating Button -->
    <button @click="toggleChat()" 
            class="chatbot-bubble w-16 h-16 bg-gradient-to-r from-green-600 to-teal-600 
                   rounded-full shadow-2xl flex items-center justify-center
                   hover:scale-110 transition-transform duration-200
                   relative group">
        <span class="text-3xl" x-show="!isOpen">💬</span>
        <svg x-show="isOpen" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        
        <!-- Badge notification -->
        <span x-show="unreadCount > 0" 
              class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold 
                     rounded-full flex items-center justify-center"
              x-text="unreadCount"></span>
        
        <!-- Tooltip -->
        <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 
                    bg-gray-900 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap
                    opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
            Assistant Bélier 🐏
        </div>
    </button>
</div>

<script>
    // Chatbot Manager pour la page Home
    function homeChatbotManager() {
        return {
            isOpen: false,
            messages: [],
            inputMessage: '',
            isTyping: false,
            unreadCount: 0,

            init() {
                // Charger l'historique depuis localStorage
                const saved = localStorage.getItem('home_chatbot_messages');
                if (saved) {
                    this.messages = JSON.parse(saved);
                }

                // Message de bienvenue après 3 secondes si premier usage
                if (this.messages.length === 0) {
                    setTimeout(() => {
                        if (!this.isOpen) {
                            this.unreadCount = 1;
                        }
                    }, 3000);
                }
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                this.unreadCount = 0;
                
                if (this.isOpen) {
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }
            },

            async sendMessage() {
                if (!this.inputMessage.trim()) return;

                const userMessage = {
                    sender: 'user',
                    text: this.inputMessage,
                    time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                };

                this.messages.push(userMessage);
                const question = this.inputMessage;
                this.inputMessage = '';
                this.isTyping = true;

                this.$nextTick(() => {
                    this.scrollToBottom();
                });

                // Simuler une réponse AI (à remplacer par vraie API plus tard)
                try {
                    const response = await this.getAIResponse(question);
                    
                    setTimeout(() => {
                        const aiMessage = {
                            sender: 'ai',
                            text: response,
                            time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                        };

                        this.messages.push(aiMessage);
                        this.isTyping = false;
                        
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });

                        // Sauvegarder l'historique
                        this.saveMessages();
                    }, 1000 + Math.random() * 1000); // Délai réaliste 1-2s

                } catch (error) {
                    this.isTyping = false;
                    console.error('Erreur chatbot:', error);
                }
            },

            async sendQuickCommand(command) {
                const commands = {
                    'articles': 'Quels articles puis-je découvrir ?',
                    'subscription': 'Comment m\'abonner à la newsletter ?',
                    'services': 'Quels services proposez-vous ?',
                    'help': 'Quelles sont tes fonctionnalités ?'
                };

                this.inputMessage = commands[command] || command;
                await this.sendMessage();
            },

            async getAIResponse(question) {
                // Réponses intelligentes adaptées au contexte utilisateur public
                const responses = {
                    'articles': '📰 <strong>Découvrez nos articles exclusifs !</strong>\n\nNous publions régulièrement du contenu sur :\n• 🔒 <strong>Sécurité informatique</strong> : Conseils et bonnes pratiques\n• 💡 <strong>Technologie</strong> : Actualités et innovations\n• 🛡️ <strong>Protection des données</strong> : RGPD et confidentialité\n• 🚀 <strong>Développement</strong> : Tutoriels et guides\n\nParcourez la page pour voir tous nos articles !',
                    
                    'subscription': '💎 <strong>Abonnez-vous gratuitement !</strong>\n\nEn vous abonnant, vous recevrez :\n✅ Articles exclusifs en avant-première\n✅ Newsletter hebdomadaire\n✅ Conseils personnalisés\n✅ Offres spéciales réservées\n\n<em>👉 Inscription rapide en haut de page !</em>',
                    
                    'services': '🛡️ <strong>Nos services Bélier Intrépide :</strong>\n\n1. <strong>Sécurité Web</strong> 🔐\n   • Audit de sécurité\n   • Protection contre les cyberattaques\n   • Conformité RGPD\n\n2. <strong>Développement</strong> 💻\n   • Applications web sécurisées\n   • Sites e-commerce\n   • Solutions sur-mesure\n\n3. <strong>Conseil IT</strong> 📊\n   • Stratégie digitale\n   • Formation équipes\n   • Support technique\n\n<em>Contactez-nous pour plus d\'infos !</em>',
                    
                    'help': '🤖 <strong>Assistant Bélier Intrépide</strong>\n\nJe suis là pour vous aider !\n\n<strong>Mes capacités :</strong>\n• 📰 Informations sur nos articles\n• 💎 Détails sur l\'abonnement\n• 🛡️ Présentation de nos services\n• ❓ Réponses à vos questions\n• 🔍 Navigation sur le site\n• 📞 Coordonnées de contact\n\n<em>Posez-moi n\'importe quelle question !</em>',
                    
                    'contact': '📧 <strong>Contactez-nous !</strong>\n\nPlusieurs moyens de nous joindre :\n\n📧 <strong>Email :</strong> contact@belierintrepide.com\n📱 <strong>Téléphone :</strong> +33 1 23 45 67 89\n🌐 <strong>Site :</strong> www.belierintrepide.com\n💬 <strong>Chat :</strong> Vous y êtes déjà ! 😊\n\n<em>Nous répondons sous 24h maximum.</em>',
                    
                    'default': '🤔 <strong>Votre question :</strong>\n\n"<em>' + question + '</em>"\n\n<strong>Je peux vous aider avec :</strong>\n• 📰 Nos articles et contenus\n• 💎 L\'abonnement newsletter\n• 🛡️ Nos services et offres\n• 📧 Nous contacter\n• 🔐 Questions de sécurité\n\nPrécisez ce que vous cherchez ! 😊'
                };

                // Détection intelligente des mots-clés
                const lowerQ = question.toLowerCase();
                
                if (lowerQ.includes('article') || lowerQ.includes('contenu') || lowerQ.includes('blog') || lowerQ.includes('lire')) {
                    return responses.articles;
                } else if (lowerQ.includes('abonn') || lowerQ.includes('newsletter') || lowerQ.includes('inscri') || lowerQ.includes('gratuit')) {
                    return responses.subscription;
                } else if (lowerQ.includes('service') || lowerQ.includes('offre') || lowerQ.includes('propose') || lowerQ.includes('faire')) {
                    return responses.services;
                } else if (lowerQ.includes('contact') || lowerQ.includes('joindre') || lowerQ.includes('email') || lowerQ.includes('téléphone') || lowerQ.includes('appel')) {
                    return responses.contact;
                } else if (lowerQ.includes('aide') || lowerQ.includes('help') || lowerQ.includes('fonction') || lowerQ.includes('peux') || lowerQ.includes('capable')) {
                    return responses.help;
                } else if (lowerQ.includes('bonjour') || lowerQ.includes('salut') || lowerQ.includes('hey') || lowerQ.includes('coucou')) {
                    return '👋 <strong>Bonjour !</strong> Bienvenue sur <strong>Bélier Intrépide</strong> !\n\nJe suis votre assistant virtuel. Comment puis-je vous aider aujourd\'hui ?\n\nUtilisez les boutons rapides ou posez-moi directement vos questions ! 😊';
                } else if (lowerQ.includes('merci') || lowerQ.includes('thank')) {
                    return '😊 <strong>De rien !</strong>\n\nC\'est un plaisir de vous aider. N\'hésitez pas si vous avez d\'autres questions !\n\n<em>Bonne navigation sur Bélier Intrépide ! 🐏</em>';
                } else {
                    return responses.default;
                }
            },

            scrollToBottom() {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            },

            handleScroll() {
                // Future: détecter le scroll pour charger plus de messages
            },

            saveMessages() {
                // Garder seulement les 50 derniers messages
                const toSave = this.messages.slice(-50);
                localStorage.setItem('home_chatbot_messages', JSON.stringify(toSave));
            },

            clearHistory() {
                this.messages = [];
                localStorage.removeItem('home_chatbot_messages');
            }
        };
    }
</script>
