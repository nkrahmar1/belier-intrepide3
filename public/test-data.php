<?php

// Créer les données de test directement
echo "<h1>Création des données de test</h1>";

// 1. Créer une catégorie POLITIQUE
echo "<h2>1. Création de la catégorie POLITIQUE</h2>";
try {
    $category = \App\Models\Category::create([
        'nom' => 'POLITIQUE',
        'slug' => 'politique',
        'description' => 'Articles sur la politique ivoirienne',
    ]);
    echo "✅ Catégorie créée avec ID: " . $category->id . "<br>";
} catch (Exception $e) {
    echo "❌ Erreur catégorie: " . $e->getMessage() . "<br>";
}

// 2. Créer un utilisateur admin
echo "<h2>2. Création de l'utilisateur admin</h2>";
try {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@belier.com'],
        [
            'name' => 'Administrateur Belier',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]
    );
    echo "✅ Utilisateur admin créé avec ID: " . $user->id . "<br>";
} catch (Exception $e) {
    echo "❌ Erreur utilisateur: " . $e->getMessage() . "<br>";
}

// 3. Créer un article
echo "<h2>3. Création de l'article</h2>";
try {
    $article = \App\Models\Article::create([
        'titre' => 'La Politique Moderne en Côte d\'Ivoire',
        'slug' => 'la-politique-moderne-en-cote-divoire',
        'contenu' => 'La politique est une chose très simple qui régit notre société ivoirienne. Elle englobe les décisions prises par le gouvernement, les débats publics, et les choix qui affectent la vie quotidienne des citoyens de Côte d\'Ivoire.',
        'extrait' => 'Analyse de la politique moderne en Côte d\'Ivoire',
        'image' => 'image/politique.jpg',
        'is_published' => true,
        'is_premium' => false,
        'published_at' => now(),
        'user_id' => $user->id,
        'category_id' => $category->id,
        'views_count' => 0,
    ]);
    echo "✅ Article créé avec ID: " . $article->id . "<br>";
} catch (Exception $e) {
    echo "❌ Erreur article: " . $e->getMessage() . "<br>";
}

// 4. Afficher un résumé
echo "<h2>4. Résumé des données</h2>";
echo "Catégories: " . \App\Models\Category::count() . "<br>";
echo "Utilisateurs: " . \App\Models\User::count() . "<br>";
echo "Articles: " . \App\Models\Article::count() . "<br>";

echo "<h2>5. Test terminé</h2>";
echo "<a href='/admin/articles'>→ Aller au tableau de bord admin</a><br>";
echo "<a href='/articles'>→ Voir les articles publics</a>";
