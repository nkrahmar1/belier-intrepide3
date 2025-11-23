<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Récupérer des catégories et utilisateurs existants
        $categories = Category::all();
        $users = User::all();
        
        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Assurez-vous d\'avoir des catégories et utilisateurs en base avant de lancer ce seeder.');
            return;
        }

        $articles = [
            // ARTICLES GRATUITS - Brèves
            [
                'titre' => 'Résultats des élections locales - Brève',
                'article_type' => 'breve',
                'content_quality' => 'court',
                'is_premium' => false,
                'free_download_limit' => 0,
                'unit_price' => null,
                'extrait' => 'Brève information sur les résultats électoraux...',
                'contenu' => 'Contenu court et factuel des résultats électoraux de la région...',
            ],
            [
                'titre' => 'Accident sur l\'autoroute - Fait divers',
                'article_type' => 'breve',
                'content_quality' => 'court',
                'is_premium' => false,
                'free_download_limit' => 0,
                'unit_price' => null,
                'extrait' => 'Accident de circulation ce matin...',
                'contenu' => 'Un accident impliquant deux véhicules s\'est produit ce matin...',
            ],

            // ARTICLES GRATUITS - Communiqués
            [
                'titre' => 'Communiqué du Ministère de la Santé',
                'article_type' => 'communique',
                'content_quality' => 'court',
                'is_premium' => false,
                'free_download_limit' => 0,
                'unit_price' => null,
                'extrait' => 'Nouvelles directives sanitaires...',
                'contenu' => 'Le Ministère de la Santé informe la population des nouvelles mesures...',
            ],

            // ARTICLES GRATUITS/PREMIUM - Tutoriels
            [
                'titre' => 'Comment s\'inscrire sur les listes électorales',
                'article_type' => 'tutoriel',
                'content_quality' => 'moyen',
                'is_premium' => false,
                'free_download_limit' => 3,
                'unit_price' => 500,
                'extrait' => 'Guide pratique pour l\'inscription électorale...',
                'contenu' => 'Étapes détaillées pour s\'inscrire sur les listes électorales...',
            ],

            // ARTICLES PREMIUM - Analyses
            [
                'titre' => 'Analyse économique : Impact de la crise sur l\'agriculture',
                'article_type' => 'analyse',
                'content_quality' => 'profond',
                'is_premium' => true,
                'free_download_limit' => 0,
                'unit_price' => 2000,
                'extrait' => 'Analyse approfondie des conséquences économiques...',
                'contenu' => 'Cette analyse détaillée examine l\'impact de la crise actuelle sur le secteur agricole ivoirien...',
            ],
            [
                'titre' => 'Décryptage politique : Les enjeux des prochaines élections',
                'article_type' => 'analyse',
                'content_quality' => 'profond',
                'is_premium' => true,
                'free_download_limit' => 0,
                'unit_price' => 1500,
                'extrait' => 'Analyse des stratégies politiques en présence...',
                'contenu' => 'Une analyse approfondie des différentes stratégies adoptées par les partis politiques...',
            ],

            // ARTICLES PREMIUM - Enquêtes
            [
                'titre' => 'Enquête : La corruption dans les marchés publics',
                'article_type' => 'enquete',
                'content_quality' => 'profond',
                'is_premium' => true,
                'free_download_limit' => 0,
                'unit_price' => 3000,
                'extrait' => 'Investigation sur les pratiques douteuses...',
                'contenu' => 'Cette enquête révèle des pratiques de corruption dans l\'attribution des marchés publics...',
            ],

            // ARTICLES PREMIUM - Interviews exclusives
            [
                'titre' => 'Interview exclusive avec le Ministre de l\'Économie',
                'article_type' => 'interview',
                'content_quality' => 'exclusif',
                'is_premium' => true,
                'free_download_limit' => 0,
                'unit_price' => 2500,
                'extrait' => 'Entretien exclusif sur les réformes économiques...',
                'contenu' => 'Dans cet entretien exclusif, le Ministre de l\'Économie détaille les prochaines réformes...',
            ],

            // ARTICLES MIXTES - Explicatifs
            [
                'titre' => 'Comprendre le système électoral ivoirien',
                'article_type' => 'explicatif',
                'content_quality' => 'moyen',
                'is_premium' => false,
                'free_download_limit' => 2,
                'unit_price' => 1000,
                'extrait' => 'Explication du fonctionnement électoral...',
                'contenu' => 'Guide complet pour comprendre le système électoral de la Côte d\'Ivoire...',
            ],
        ];

        foreach ($articles as $articleData) {
            $article = Article::create([
                'titre' => $articleData['titre'],
                'slug' => \Illuminate\Support\Str::slug($articleData['titre']),
                'extrait' => $articleData['extrait'],
                'contenu' => $articleData['contenu'],
                'category_id' => $categories->random()->id,
                'user_id' => $users->random()->id,
                'article_type' => $articleData['article_type'],
                'content_quality' => $articleData['content_quality'],
                'is_premium' => $articleData['is_premium'],
                'free_download_limit' => $articleData['free_download_limit'],
                'unit_price' => $articleData['unit_price'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 30)),
                'views_count' => rand(10, 1000),
                'download_count' => rand(0, 100),
                'storage_size' => rand(50000, 5000000), // Entre 50KB et 5MB
            ]);

            $this->command->info("Article créé : {$article->titre} ({$article->article_type})");
        }

        $this->command->info('Seeder ArticleType terminé avec succès !');
    }
}
