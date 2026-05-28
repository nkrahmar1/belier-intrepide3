// ===============================================
// SCRIPT DE DIAGNOSTIC ET CORRECTION NAVBAR
// ===============================================
// Copiez et collez ce code dans la console de votre navigateur (F12)

console.log('🚀 Début du diagnostic navbar...');

// 1. Vérifier si Bootstrap est disponible
console.log('--- Test 1: Bootstrap ---');
if (typeof bootstrap !== 'undefined') {
    console.log('✅ Bootstrap est chargé');
    console.log('Version:', bootstrap.Tooltip.VERSION || 'Version inconnue');
} else {
    console.error('❌ Bootstrap n\'est PAS chargé !');
    console.log('🔧 Solution: Vérifiez que Bootstrap JS est inclus dans votre page');
}

// 2. Compter les éléments dropdown
console.log('--- Test 2: Éléments dropdown ---');
const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
console.log('Nombre de dropdowns trouvés:', dropdowns.length);

if (dropdowns.length === 0) {
    console.warn('⚠️ Aucun dropdown trouvé !');
    console.log('🔧 Vérifiez que vos boutons ont l\'attribut data-bs-toggle="dropdown"');
} else {
    dropdowns.forEach(function(dropdown, index) {
        console.log('Dropdown ' + (index + 1) + ':', dropdown);
    });
}

// 3. Vérifier les instances Bootstrap existantes
console.log('--- Test 3: Instances Bootstrap ---');
dropdowns.forEach(function(dropdown, index) {
    const instance = bootstrap.Dropdown.getInstance(dropdown);
    if (instance) {
        console.log('✅ Dropdown ' + (index + 1) + ' a déjà une instance Bootstrap');
    } else {
        console.log('⚠️ Dropdown ' + (index + 1) + ' n\'a pas d\'instance Bootstrap');
    }
});

// 4. Initialiser/réinitialiser tous les dropdowns
console.log('--- Test 4: Initialisation forcée ---');
let successCount = 0;
let errorCount = 0;

dropdowns.forEach(function(dropdown, index) {
    try {
        // Détruire l'instance existante si elle existe
        const existingInstance = bootstrap.Dropdown.getInstance(dropdown);
        if (existingInstance) {
            existingInstance.dispose();
        }
        
        // Créer une nouvelle instance
        new bootstrap.Dropdown(dropdown);
        console.log('✅ Dropdown ' + (index + 1) + ' initialisé avec succès');
        successCount++;
    } catch (error) {
        console.error('❌ Erreur dropdown ' + (index + 1) + ':', error);
        errorCount++;
    }
});

// 5. Résumé
console.log('--- RÉSUMÉ ---');
console.log('Dropdowns trouvés:', dropdowns.length);
console.log('Initialisés avec succès:', successCount);
console.log('Erreurs:', errorCount);

if (errorCount === 0 && dropdowns.length > 0) {
    console.log('🎉 SUCCÈS ! Tous les dropdowns sont maintenant fonctionnels');
    console.log('🧪 Testez maintenant en cliquant sur les boutons de votre navbar');
} else if (dropdowns.length === 0) {
    console.log('🔍 AUCUN DROPDOWN TROUVÉ');
    console.log('🔧 Vérifiez que votre navbar contient des éléments avec data-bs-toggle="dropdown"');
} else {
    console.log('⚠️ ERREURS DÉTECTÉES');
    console.log('🔧 Vérifiez les erreurs ci-dessus pour plus de détails');
}

// 6. Bonus: Ajouter des écouteurs d'événements pour le debug
console.log('--- Ajout des écouteurs de debug ---');
document.addEventListener('show.bs.dropdown', function(event) {
    console.log('🔽 Dropdown ouvert:', event.target.textContent.trim());
});

document.addEventListener('hide.bs.dropdown', function(event) {
    console.log('🔼 Dropdown fermé:', event.target.textContent.trim());
});

console.log('✅ Script de diagnostic terminé !');
console.log('📋 Maintenant, testez vos dropdowns en cliquant dessus');
