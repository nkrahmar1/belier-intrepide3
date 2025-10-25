<?php

namespace App\Console\Commands;

use App\Models\Categorie;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RegenerateCategorySlugs extends Command
{
    protected $signature = 'categories:regenerate-slugs';
    protected $description = 'Régénère les slugs pour toutes les catégories';

    public function handle()
    {
        $this->info('Début de la régénération des slugs...');

        Categorie::chunk(100, function ($categories) {
            foreach ($categories as $category) {
                $baseSlug = Str::slug($category->nom);
                $slug = $baseSlug;
                $count = 1;

                while (Categorie::where('slug', $slug)
                    ->where('id', '!=', $category->id)
                    ->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }

                $category->slug = $slug;
                $category->save();

                $this->line("Slug généré pour la catégorie {$category->nom}: {$slug}");
            }
        });

        $this->info('Régénération des slugs terminée !');
    }
}
