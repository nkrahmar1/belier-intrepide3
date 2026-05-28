<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création de 5 catégories
        $categories = \App\Models\Category::factory(5)->create();

        // Création de 20 utilisateurs
        $users = \App\Models\User::factory(20)->create();

        // Création de 30 articles associés à des utilisateurs et catégories
        \App\Models\Article::factory(30)->make()->each(function ($article) use ($users, $categories) {
            $article->user_id = $users->random()->id;
            $article->category_id = $categories->random()->id;
            $article->save();
        });

        // Création de 15 abonnements
        \App\Models\Subscription::factory(15)->make()->each(function ($subscription) use ($users) {
            $subscription->user_id = $users->random()->id;
            $subscription->save();
        });
    }
}
