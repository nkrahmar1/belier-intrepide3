// === SCRIPT ULTRA-SIMPLE POUR TEST IMMÉDIAT ===
// Copiez ce code et collez-le dans la console (F12)

console.log('🚨 DÉBUT DU TEST IMMÉDIAT');

// 1. Trouver tous les articles
const articles = document.querySelectorAll('[data-category]');
console.log('📊 Articles trouvés:', articles.length);

if (articles.length === 0) {
    alert('❌ AUCUN ARTICLE TROUVÉ!\n\nAssurez-vous d\'être sur la page d\'accueil (/) avec des articles.');
} else {
    console.log('✅ Articles détectés - Test possible');
    
    // 2. Créer un bouton de test visible
    const btn = document.createElement('div');
    btn.innerHTML = '🎯 CLIQUEZ ICI<br>TEST ÉCONOMIE';
    btn.style.cssText = `
        position: fixed; top: 50%; right: 20px; transform: translateY(-50%);
        z-index: 999999; background: red; color: white; padding: 20px;
        border-radius: 10px; cursor: pointer; font-weight: bold;
        font-size: 14px; text-align: center; border: 3px solid white;
        box-shadow: 0 0 20px rgba(255,0,0,0.5); min-width: 120px;
    `;
    
    btn.onclick = function() {
        console.log('🎯 TEST DÉCLENCHÉ');
        
        let economie = 0, autres = 0;
        
        articles.forEach(article => {
            const cat = article.getAttribute('data-category');
            
            if (cat === '1') {
                // Économie - VERT
                article.style.background = 'green';
                article.style.border = '5px solid darkgreen';
                article.style.color = 'white';
                economie++;
            } else {
                // Autres - GRIS
                article.style.background = 'lightgray';
                article.style.border = '2px solid gray';
                article.style.color = 'black';
                autres++;
            }
        });
        
        alert(`✅ TEST TERMINÉ!\n\n🟢 ${economie} articles ÉCONOMIE (verts)\n⚫ ${autres} autres articles (gris)`);
        console.log(`✅ Résultat: ${economie} économie, ${autres} autres`);
    };
    
    document.body.appendChild(btn);
    
    console.log('🔴 BOUTON ROUGE AJOUTÉ À DROITE - CLIQUEZ DESSUS!');
    alert('✅ Script chargé!\n\nUn bouton rouge "TEST ÉCONOMIE" est apparu à droite.\n\nCliquez dessus pour tester le filtrage!');
}