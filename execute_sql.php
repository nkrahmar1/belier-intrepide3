<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Connexion à la base de données...\n";

try {
    // Lire le fichier SQL
    $sql = file_get_contents('articles_test.sql');

    echo "Exécution des requêtes SQL...\n";

    // Exécuter les requêtes
    DB::unprepared($sql);

    echo "✅ Requêtes exécutées avec succès!\n";

    // Vérifier les données
    $categories = DB::table('categories')->count();
    $users = DB::table('users')->count();
    $articles = DB::table('articles')->count();

    echo "\n📊 Données dans la base:\n";
    echo "- Catégories: $categories\n";
    echo "- Utilisateurs: $users\n";
    echo "- Articles: $articles\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
