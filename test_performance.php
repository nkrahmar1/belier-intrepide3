<?php

// Test simple de performance pour vérifier les optimisations
$start_time = microtime(true);

echo "=== TEST DE PERFORMANCE ===\n";
echo "Début du test : " . date('H:i:s') . "\n\n";

// Test 1: Chargement de base
echo "1. Test de chargement de base...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8000/articles");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$test1_start = microtime(true);
$response = curl_exec($ch);
$test1_end = microtime(true);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$response_time = round(($test1_end - $test1_start) * 1000, 2);

echo "   - Code HTTP: $http_code\n";
echo "   - Temps de réponse: {$response_time}ms\n";

if ($http_code == 200) {
    echo "   - ✅ Page chargée avec succès\n";
    $content_length = strlen($response);
    echo "   - Taille de la réponse: " . round($content_length / 1024, 2) . " KB\n";
} else {
    echo "   - ❌ Erreur de chargement\n";
}

curl_close($ch);

echo "\n";

// Test 2: Test de charge multiple
echo "2. Test de charge (5 requêtes simultanées)...\n";
$multi = curl_multi_init();
$curl_handles = [];

for ($i = 0; $i < 5; $i++) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8000/articles");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $curl_handles[] = $ch;
    curl_multi_add_handle($multi, $ch);
}

$test2_start = microtime(true);

// Exécuter toutes les requêtes
$running = null;
do {
    curl_multi_exec($multi, $running);
    curl_multi_select($multi);
} while ($running > 0);

$test2_end = microtime(true);
$total_time = round(($test2_end - $test2_start) * 1000, 2);

echo "   - Temps total pour 5 requêtes: {$total_time}ms\n";
echo "   - Temps moyen par requête: " . round($total_time / 5, 2) . "ms\n";

// Vérifier les résultats
$success_count = 0;
foreach ($curl_handles as $ch) {
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code == 200) {
        $success_count++;
    }
    curl_multi_remove_handle($multi, $ch);
    curl_close($ch);
}

curl_multi_close($multi);

echo "   - Requêtes réussies: $success_count/5\n";

if ($success_count == 5) {
    echo "   - ✅ Test de charge réussi\n";
} else {
    echo "   - ⚠️  Quelques échecs dans le test de charge\n";
}

$end_time = microtime(true);
$total_test_time = round(($end_time - $start_time) * 1000, 2);

echo "\n=== RÉSUMÉ ===\n";
echo "Temps total du test: {$total_test_time}ms\n";
echo "Test terminé : " . date('H:i:s') . "\n";

if ($response_time < 2000 && $success_count >= 4) {
    echo "✅ Performance acceptable - Optimisations efficaces\n";
} elseif ($response_time < 5000) {
    echo "⚠️  Performance modérée - Optimisations partielles\n";
} else {
    echo "❌ Performance faible - Optimisations supplémentaires nécessaires\n";
}

echo "\nConseil: Si les temps sont encore élevés, vérifiez :\n";
echo "- La taille de la base de données\n";
echo "- La configuration PHP (memory_limit, max_execution_time)\n";
echo "- Les requêtes SQL non optimisées\n";
echo "- La mise en cache\n";

?>
