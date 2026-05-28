<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            
            // Champs essentiels
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('contenu');
            $table->text('extrait')->nullable();
            
            // Fichiers (simplifiés)
            $table->string('image')->nullable();
            $table->string('document_path')->nullable();
            
            // Statuts (essentiels)
            $table->boolean('is_published')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->timestamp('published_at')->nullable();
            
            // Relations (obligatoires)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // Statistiques basiques
            $table->unsignedInteger('views_count')->default(0);
            
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['is_published', 'published_at']);
            $table->index(['category_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
