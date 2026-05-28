<!-- Boutons de debug supprimés -->

<script>
function reinitNavbarDropdowns() {
    console.log('🔧 Réinitialisation manuelle des dropdowns...');

    if (typeof bootstrap === 'undefined') {
        alert('Bootstrap non chargé !');
        return;
    }

    const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    let success = 0;

    dropdowns.forEach(function(element) {
        try {
            // Supprimer instance existante
            const existing = bootstrap.Dropdown.getInstance(element);
            if (existing) {
                existing.dispose();
            }

            // Créer nouvelle instance
            new bootstrap.Dropdown(element);
            success++;

        } catch (error) {
            console.error('Erreur dropdown:', element.id, error);
        }
    });

    console.log('✅ Dropdowns réinitialisés:', success + '/' + dropdowns.length);
    alert('Dropdowns réinitialisés: ' + success + '/' + dropdowns.length);
}

function testDropdownsManually() {
    console.log('🧪 Test manuel des dropdowns...');

    const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');

    if (dropdowns.length === 0) {
        alert('Aucun dropdown trouvé !');
        return;
    }

    let results = [];

    dropdowns.forEach(function(element, index) {
        const id = element.id || 'dropdown-' + (index + 1);
        const instance = bootstrap.Dropdown.getInstance(element);
        const hasInstance = !!instance;

        results.push(id + ': ' + (hasInstance ? '✅ OK' : '❌ NO'));

        // Test de clic programmé
        try {
            element.click();
            results[results.length - 1] += ' (Click OK)';
        } catch (error) {
            results[results.length - 1] += ' (Click Error)';
        }
    });

    alert('Test des dropdowns:\n' + results.join('\n'));
    console.log('🧪 Résultats test:', results);
}
</script>
