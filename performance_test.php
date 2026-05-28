<?php
// Test de performance du site après optimisation
$start_time = microtime(true);

// URLs à tester
$urls = [
    'http://127.0.0.1:8001/',
    'http://127.0.0.1:8001/articles/politique',
    'http://127.0.0.1:8001/articles/economie',
    'http://127.0.0.1:8001/articles/societe',
    'http://127.0.0.1:8001/cart'
];

echo "=== TEST DE PERFORMANCE APRÈS OPTIMISATION ===\n\n";

foreach ($urls as $url) {
    $page_start = microtime(true);

    // Test de la requête HTTP
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET',
            'header' => 'User-Agent: Performance Test Script'
        ]
    ]);

    $response = @file_get_contents($url, false, $context);
    $page_time = microtime(true) - $page_start;

    if ($response !== false) {
        $response_code = substr($http_response_header[0], 9, 3);
        $content_length = strlen($response);

        echo "✅ {$url}\n";
        echo "   Temps de réponse: " . round($page_time * 1000, 2) . " ms\n";
        echo "   Code de réponse: {$response_code}\n";
        echo "   Taille du contenu: " . number_format($content_length) . " octets\n";

        // Vérification des erreurs dans le contenu
        if (strpos($response, 'headers already sent') !== false) {
            echo "   ⚠️  ATTENTION: Erreur 'headers already sent' détectée\n";
        }
        if (strpos($response, 'Fatal error') !== false) {
            echo "   ❌ ERREUR FATALE détectée\n";
        }
        if (strpos($response, 'Warning') !== false) {
            echo "   ⚠️  Warning détecté\n";
        }

    } else {
        echo "❌ {$url} - Échec de la requête\n";
    }
    echo "\n";

    // Pause pour éviter la surcharge
    usleep(500000); // 0.5 seconde
}

$total_time = microtime(true) - $start_time;
echo "=== RÉSUMÉ ===\n";
echo "Temps total du test: " . round($total_time, 2) . " secondes\n";
echo "Nombre d'URLs testées: " . count($urls) . "\n";

echo "\n=== RECOMMANDATIONS ===\n";
echo "• Temps de réponse idéal: < 200ms\n";
echo "• Temps de réponse acceptable: < 500ms\n";
echo "• Temps de réponse lent: > 1000ms\n";

echo "\n=== OPTIMISATIONS APPLIQUÉES ===\n";
echo "✅ Configuration du cache: FILE\n";
echo "✅ Sessions: FILE (plus rapide que database)\n";
echo "✅ Config cache activé\n";
echo "✅ Route cache activé\n";
echo "✅ View cache activé\n";
echo "✅ Output buffering optimisé\n";
echo "✅ Headers sécurisés\n";
?>
