// ===== VERSION CORRIGÉE DU CODE CHATBOT =====

// Fonction pour ajouter un message au chat
function addMessageToChat(message, type) {
    const time = new Date().toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });

    const messageDiv = document.createElement('div');
    messageDiv.className = `mb-4 ${type === 'user' ? 'text-right' : 'text-left'}`;

    if (type === 'user') {
        messageDiv.innerHTML = `
            <div class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg max-w-xs">
                👤 ${message}
                <div class="text-xs opacity-75 mt-1">${time}</div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-xs">
                🤖 ${message}
                <div class="text-xs opacity-75 mt-1">${time}</div>
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
            if (welcomeMessage) {
                chatMessages.appendChild(welcomeMessage);
            }

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

// ===== PRINCIPALES CORRECTIONS APPORTÉES =====
/*
1. Ajout du .catch() manquant dans la requête fetch
2. Correction de "notificationBadg" en "notificationBadge"
3. Ajout de vérification pour welcomeMessage avant appendChild
4. Structure complète du code avec gestion d'erreurs
5. Formatage correct du HTML dans addMessageToChat
6. Gestion appropriée des classes CSS pour les notifications
*/
