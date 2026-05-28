/**
 * Script de correction Bootstrap pour les problèmes d'inspecteur
 * Ce script force la réinitialisation des composants Bootstrap
 * Version optimisée pour les dropdowns Account et Panier
 */

(function() {
    'use strict';

    let isDevToolsOpen = false;
    let initialized = false;

    // Fonction principale pour initialiser Bootstrap
    function forceBootstrapInit() {
        if (typeof bootstrap === 'undefined') {
            console.warn('Bootstrap non disponible, nouvelle tentative...');
            setTimeout(forceBootstrapInit, 200);
            return;
        }

        console.log('🔄 Initialisation forcée des composants Bootstrap');

        try {
            // Nettoyer toutes les instances existantes
            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(element) {
                const instance = bootstrap.Dropdown.getInstance(element);
                if (instance) {
                    instance.dispose();
                }
            });

            // Réinitialiser tous les dropdowns
            const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            let successCount = 0;

            dropdownElements.forEach(function(element, index) {
                try {
                    const dropdown = new bootstrap.Dropdown(element);

                    // Ajouter des listeners de debug
                    element.addEventListener('click', function(e) {
                        console.log('🖱️ Click sur dropdown:', this.id || this.className);
                    });

                    element.addEventListener('shown.bs.dropdown', function() {
                        console.log('✅ Dropdown ouvert:', this.id || this.className);
                    });

                    element.addEventListener('hidden.bs.dropdown', function() {
                        console.log('❌ Dropdown fermé:', this.id || this.className);
                    });

                    successCount++;
                } catch (error) {
                    console.error('❌ Erreur dropdown #' + index + ':', error);
                }
            });

            console.log('✅ Dropdowns initialisés:', successCount + '/' + dropdownElements.length);
            initialized = true;

        } catch (error) {
            console.error('❌ Erreur générale initialisation Bootstrap:', error);
        }
    }

    // Détecter l'ouverture/fermeture des outils de développement
    function detectDevToolsChange() {
        const threshold = 160;
        const widthThreshold = window.outerWidth - window.innerWidth > threshold;
        const heightThreshold = window.outerHeight - window.innerHeight > threshold;
        const devToolsOpen = widthThreshold || heightThreshold;

        if (devToolsOpen !== isDevToolsOpen) {
            isDevToolsOpen = devToolsOpen;

            if (devToolsOpen) {
                console.log('🔧 DevTools ouvert');
            } else {
                console.log('🔧 DevTools fermé - Réinitialisation Bootstrap...');
                setTimeout(forceBootstrapInit, 300);
            }
        }
    }

    // Surveiller les changements de taille (indicateur DevTools)
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            detectDevToolsChange();
            if (!isDevToolsOpen) {
                forceBootstrapInit();
            }
        }, 250);
    });

    // Initialisation au chargement
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(forceBootstrapInit, 500);
        });
    } else {
        setTimeout(forceBootstrapInit, 500);
    }

    // Réinitialisation périodique pour être sûr
    setInterval(function() {
        if (!initialized || !isDevToolsOpen) {
            const dropdownCount = document.querySelectorAll('[data-bs-toggle="dropdown"]').length;
            const instanceCount = document.querySelectorAll('[data-bs-toggle="dropdown"]').length;

            if (dropdownCount > 0 && instanceCount === 0) {
                console.log('🔄 Réinitialisation périodique détectée comme nécessaire');
                forceBootstrapInit();
            }
        }
    }, 3000);

    // Exporter pour usage manuel dans la console
    window.fixBootstrap = forceBootstrapInit;
    window.checkBootstrap = function() {
        console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');
        console.log('Dropdowns trouvés:', document.querySelectorAll('[data-bs-toggle="dropdown"]').length);
        console.log('DevTools ouvert:', isDevToolsOpen);
        console.log('Initialisé:', initialized);
    };

})();
