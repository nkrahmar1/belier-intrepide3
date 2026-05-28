<?php
// Diagnostic navbar Bootstrap - test_navbar_diagnostic.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Navbar Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .diagnostic-item {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .success { background-color: #d4edda; border-left: 4px solid #28a745; }
        .error { background-color: #f8d7da; border-left: 4px solid #dc3545; }
        .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; }
        .info { background-color: #d1ecf1; border-left: 4px solid #17a2b8; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">🔍 Diagnostic Navbar Laravel</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h3>📊 État du système</h3>
                <div id="system-status"></div>
                
                <h3 class="mt-4">🧪 Test navbar réel</h3>
                <p>Allez sur votre site pour tester :</p>
                <a href="http://127.0.0.1:8003" class="btn btn-primary" target="_blank">
                    <i class="bi bi-arrow-up-right-square"></i> Ouvrir le site
                </a>
                
                <h3 class="mt-4">📋 Instructions de test</h3>
                <ol>
                    <li>Cliquez sur le bouton "Mon compte" → Le menu doit s'ouvrir</li>
                    <li>Cliquez sur "Se déconnecter" → Une confirmation doit apparaître</li>
                    <li>Cliquez sur l'icône panier → Le menu doit s'ouvrir</li>
                    <li>Vérifiez que les dropdowns se ferment en cliquant ailleurs</li>
                </ol>
            </div>
            
            <div class="col-md-6">
                <h3>🔽 Test de base Bootstrap</h3>
                
                <!-- Test dropdown simple -->
                <div class="dropdown mb-3">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Test Mon Compte
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="logTest('Se connecter cliqué')">Se Connecter</a></li>
                        <li><a class="dropdown-item" href="#" onclick="logTest('S inscrire cliqué')">S'inscrire</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" onclick="logTest('Déconnexion cliquée')">
                                <i class="bi bi-box-arrow-right"></i> Se déconnecter
                            </button>
                        </li>
                    </ul>
                </div>
                
                <!-- Test dropdown panier -->
                <div class="dropdown mb-3">
                    <a href="#" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-cart3"></i> Test Panier
                        <span class="badge bg-danger">2</span>
                    </a>
                    <ul class="dropdown-menu" style="min-width: 250px;">
                        <li class="px-3 py-2">
                            <strong>Articles dans le panier :</strong>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-3 py-2">
                            <div class="d-flex justify-content-between">
                                <span>Article test</span>
                                <button class="btn btn-sm btn-outline-danger" onclick="logTest('Supprimer article')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-3 py-2">
                            <button class="btn btn-primary btn-sm w-100" onclick="logTest('Voir panier complet')">
                                Voir le panier
                            </button>
                        </li>
                    </ul>
                </div>
                
                <h4>Console de test :</h4>
                <div id="test-log" class="border p-3" style="height: 200px; overflow-y: auto; background: #f8f9fa; font-family: monospace; font-size: 12px;">
                    <div class="text-success">[INFO] Console initialisée</div>
                </div>
                <button class="btn btn-secondary btn-sm mt-2" onclick="clearLog()">Effacer</button>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h3>🛠️ Solutions communes</h3>
                <div class="accordion" id="solutionsAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solution1">
                                Bootstrap ne se charge pas
                            </button>
                        </h2>
                        <div id="solution1" class="accordion-collapse collapse" data-bs-parent="#solutionsAccordion">
                            <div class="accordion-body">
                                <strong>Vérifiez :</strong>
                                <ul>
                                    <li>L'ordre de chargement des scripts (Bootstrap JS avant vos scripts)</li>
                                    <li>Pas d'erreurs 404 dans la console navigateur</li>
                                    <li>Internet fonctionne pour charger le CDN Bootstrap</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solution2">
                                Les dropdowns ne s'ouvrent pas
                            </button>
                        </h2>
                        <div id="solution2" class="accordion-collapse collapse" data-bs-parent="#solutionsAccordion">
                            <div class="accordion-body">
                                <strong>Solutions :</strong>
                                <ul>
                                    <li>Vérifier l'attribut <code>data-bs-toggle="dropdown"</code></li>
                                    <li>S'assurer que Bootstrap JS est chargé après Bootstrap CSS</li>
                                    <li>Initialiser manuellement : <code>new bootstrap.Dropdown(element)</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solution3">
                                Erreurs dans la console
                            </button>
                        </h2>
                        <div id="solution3" class="accordion-collapse collapse" data-bs-parent="#solutionsAccordion">
                            <div class="accordion-body">
                                <strong>Outils de débogage :</strong>
                                <ul>
                                    <li>Ouvrir les outils développeur (F12)</li>
                                    <li>Regarder l'onglet Console pour les erreurs</li>
                                    <li>Vérifier l'onglet Réseau pour les échecs de chargement</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const testLog = document.getElementById('test-log');
        const systemStatus = document.getElementById('system-status');
        
        function logTest(message) {
            const time = new Date().toLocaleTimeString();
            testLog.innerHTML += `<div class="text-primary">[${time}] ${message}</div>`;
            testLog.scrollTop = testLog.scrollHeight;
        }
        
        function clearLog() {
            testLog.innerHTML = '<div class="text-success">[INFO] Console effacée</div>';
        }
        
        function checkSystem() {
            let status = '';
            
            // Vérifier Bootstrap
            if (typeof bootstrap !== 'undefined') {
                status += '<div class="diagnostic-item success">✅ Bootstrap JS chargé et disponible</div>';
            } else {
                status += '<div class="diagnostic-item error">❌ Bootstrap JS non chargé</div>';
            }
            
            // Vérifier jQuery (optionnel)
            if (typeof $ !== 'undefined') {
                status += '<div class="diagnostic-item info">ℹ️ jQuery détecté (optionnel)</div>';
            } else {
                status += '<div class="diagnostic-item warning">⚠️ jQuery non détecté (normal avec Bootstrap 5)</div>';
            }
            
            // Vérifier les dropdowns
            const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            if (dropdowns.length > 0) {
                status += `<div class="diagnostic-item success">✅ ${dropdowns.length} dropdown(s) trouvé(s)</div>`;
            } else {
                status += '<div class="diagnostic-item warning">⚠️ Aucun dropdown trouvé</div>';
            }
            
            systemStatus.innerHTML = status;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            logTest('DOM chargé');
            checkSystem();
            
            // Écouter les événements dropdown
            document.addEventListener('show.bs.dropdown', function(e) {
                logTest('Dropdown ouvert: ' + e.target.textContent.trim());
            });
            
            document.addEventListener('hide.bs.dropdown', function(e) {
                logTest('Dropdown fermé: ' + e.target.textContent.trim());
            });
            
            logTest('Diagnostic initialisé - Testez les dropdowns !');
        });
    </script>
</body>
</html>
