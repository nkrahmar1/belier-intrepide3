<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CRÉATION DIRECTE DE DONNÉES ===\n\n";

try {
    // 1. Créer une catégorie
    echo "1. Création de la catégorie...\n";
    DB::table('categories')->insertOrIgnore([
        'id' => 1,
        'nom' => 'POLITIQUE',
        'slug' => 'politique', 
        'description' => 'Articles politiques',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Catégorie créée\n";

    // 2. Créer un utilisateur admin
    echo "2. Création de l'utilisateur admin...\n";
    DB::table('users')->insertOrIgnore([
        'id' => 1,
        'name' => 'Admin',
        'email' => 'admin@belier.com',
        'password' => bcrypt('admin123'),
        'is_admin' => true,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Utilisateur admin créé\n";

    // 3. Créer des articles publiés
    echo "3. Création des articles...\n";
    
    $articles = [
        [
            'id' => 1,
            'titre' => 'La Politique Moderne en Côte d\'Ivoire',
            'slug' => 'la-politique-moderne-en-cote-divoire',
            'contenu' => 'La politique est une chose très importante qui régit notre société ivoirienne. Elle englobe les décisions prises par le gouvernement, les débats publics, et les choix qui affectent la vie quotidienne des citoyens de Côte d\'Ivoire.',
            'extrait' => 'Analyse de la politique moderne en Côte d\'Ivoire',
            'category_id' => 1,
            'user_id' => 1,
            'is_published' => true,
            'is_premium' => false,
            'published_at' => now(),
            'views_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'id' => 2,
            'titre' => 'Économie Ivoirienne : Perspectives 2025',
            'slug' => 'economie-ivoirienne-perspectives-2025',
            'contenu' => 'L\'économie de la Côte d\'Ivoire connaît une croissance remarquable. Avec ses ressources naturelles et sa position stratégique en Afrique de l\'Ouest, le pays s\'impose comme un acteur majeur du développement économique régional.',
            'extrait' => 'Les perspectives économiques pour 2025',
            'category_id' => 1,
            'user_id' => 1,
            'is_published' => true,
            'is_premium' => false,
            'published_at' => now(),
            'views_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'id' => 3,
            'titre' => 'Culture et Traditions Ivoiriennes',
            'slug' => 'culture-et-traditions-ivoiriennes',
            'contenu' => 'La richesse culturelle de la Côte d\'Ivoire se manifeste à travers ses nombreuses ethnies, ses traditions ancestrales et son art contemporain. Chaque région apporte sa contribution unique au patrimoine national.',
            'extrait' => 'Découverte de la richesse culturelle ivoirienne',
            'category_id' => 1,
            'user_id' => 1,
            'is_published' => true,
            'is_premium' => false,
            'published_at' => now(),
            'views_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];

    foreach ($articles as $article) {
        DB::table('articles')->insertOrIgnore($article);
        echo "✅ Article '{$article['titre']}' créé\n";
    }

    // 4. Vérification des données
    echo "\n=== VÉRIFICATION ===\n";
    $categoriesCount = DB::table('categories')->count();
    $usersCount = DB::table('users')->count();
    $articlesCount = DB::table('articles')->count();
    $publishedCount = DB::table('articles')->where('is_published', true)->count();

    echo "📊 Statistiques :\n";
    echo "- Catégories: $categoriesCount\n";
    echo "- Utilisateurs: $usersCount\n";
    echo "- Articles total: $articlesCount\n";
    echo "- Articles publiés: $publishedCount\n\n";

    echo "🎉 DONNÉES CRÉÉES AVEC SUCCÈS !\n";
    echo "Vous pouvez maintenant accéder à :\n";
    echo "- Articles: http://127.0.0.1:8000/articles\n";
    echo "- Dashboard: http://127.0.0.1:8000/admin/dashboard\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
