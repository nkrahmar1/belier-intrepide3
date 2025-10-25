<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(),
            'contenu' => $this->faker->paragraph(5),
            'category_id' => Category::factory(),
            'image' => $this->faker->imageUrl(640, 480, 'articles'),
            'user_id' => User::factory(),
            'file_name' => $this->faker->word() . '.pdf',
            'is_published' => $this->faker->boolean(80),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
