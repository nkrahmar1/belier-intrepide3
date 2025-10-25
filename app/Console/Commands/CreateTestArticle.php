<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

class CreateTestArticle extends Command
{
    protected $signature = 'create:test-article';
    protected $description = 'Créer un article de test politique';

    public function handle()
    {
        // Vérifier ou créer un utilisateur
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'is_admin' => true
            ]);
            $this->info('Utilisateur créé: ' . $user->email);
        }

        // Vérifier ou créer une catégorie POLITIQUE
        $category = Category::where('slug', 'politique')->first();
        if (!$category) {
            $category = Category::create([
                'nom' => 'POLITIQUE',
                'slug' => 'politique',
                'description' => 'Articles sur la politique et la gouvernance'
            ]);
            $this->info('Catégorie créée: ' . $category->nom);
        }

        // Créer l'article
        $article = Article::create([
            'titre' => 'La Politique Moderne en Côte d\'Ivoire',
            'slug' => 'la-politique-moderne-en-cote-divoire',
            'contenu' => 'La politique est une chose très simple qui régit notre société ivoirienne. Elle englobe les décisions prises par le gouvernement, les débats publics, et les choix qui affectent la vie quotidienne des citoyens de Côte d\'Ivoire. Dans ce contexte actuel, il est important de comprendre les enjeux politiques et leur impact sur le développement du pays.',
            'extrait' => 'Analyse de la politique moderne et de la gouvernance en Côte d\'Ivoire',
            'image' => 'image/politique.jpg', // Image existante dans votre dossier public
            'document_path' => null,
            'file_original_name' => null,
            'file_size' => null,
            'is_published' => true,
            'is_premium' => false,
            'published_at' => now(),
            'user_id' => $user->id,
            'category_id' => $category->id,
            'views_count' => 0
        ]);

        $this->info('Article créé avec succès !');
        $this->info('ID: ' . $article->id);
        $this->info('Titre: ' . $article->titre);
        $this->info('Publié: ' . ($article->is_published ? 'OUI' : 'NON'));
        $this->info('URL: /articles/' . $article->id);

        return 0;
    }
}
