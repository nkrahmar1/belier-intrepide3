// === SCRIPT DE PROTECTION JAVASCRIPT DASHBOARD ===
// À ajouter au début de la section script du dashboard

// Protection globale pour innerHTML
function safeSetInnerHTML(element, content) {
    if (element && typeof element.innerHTML !== 'undefined') {
        element.innerHTML = content;
        return true;
    } else {
        console.warn('Élément null ou innerHTML non supporté:', element);
        return false;
    }
}

// Protection globale pour les appels fetch
function safeFetch(url, options = {}) {
    console.log('Appel fetch vers:', url);

    return fetch(url, options)
        .then(response => {
            console.log('Réponse fetch:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response;
        })
        .catch(error => {
            console.error('Erreur fetch:', error);
            showNotification('Erreur de connexion: ' + error.message, 'error');
            throw error;
        });
}

// Fonction de notification améliorée avec protection
function showNotification(message, type = 'info') {
    const types = {
        success: { bg: 'bg-green-500', icon: '✅' },
        error: { bg: 'bg-red-500', icon: '❌' },
        warning: { bg: 'bg-yellow-500', icon: '⚠️' },
        info: { bg: 'bg-blue-500', icon: 'ℹ️' }
    };

    const config = types[type] || types.info;

    const notification = document.createElement('div');
    if (!notification) {
        console.error('Impossible de créer l\'élément notification');
        return;
    }

    notification.className = `fixed top-4 right-4 ${config.bg} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;

    safeSetInnerHTML(notification, `
        <div class="flex items-center gap-3">
            <span class="text-lg">${config.icon}</span>
            <span class="font-medium">${message}</span>
        </div>
    `);

    if (document.body) {
        document.body.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Suppression automatique
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    } else {
        console.error('document.body non disponible pour la notification');
    }
}

// Protection globale des erreurs JavaScript
window.addEventListener('error', function(event) {
    console.error('Erreur JavaScript globale:', event.error);
    showNotification('Erreur JavaScript: ' + event.message, 'error');
});

// Protection des promesses rejetées
window.addEventListener('unhandledrejection', function(event) {
    console.error('Promesse rejetée:', event.reason);
    showNotification('Erreur de promesse: ' + event.reason, 'error');
});

console.log('🛡️ Protections JavaScript dashboard chargées');
