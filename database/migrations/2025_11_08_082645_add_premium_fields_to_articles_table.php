<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà
            if (!Schema::hasColumn('articles', 'article_type')) {
                // Type d'article selon votre classification
                $table->enum('article_type', [
                    'breve',           // Brèves, faits divers - Gratuit
                    'communique',      // Communiqués - Gratuit  
                    'analyse',         // Analyses politiques, économiques - Premium
                    'enquete',         // Enquêtes approfondies - Premium
                    'interview',       // Interviews exclusives - Premium
                    'tutoriel',        // Comment faire - Gratuit/Premium selon valeur
                    'explicatif'       // Comprendre le sujet - Gratuit/Premium
                ])->default('breve')->after('is_premium');
            }
            
            if (!Schema::hasColumn('articles', 'content_quality')) {
                // Niveau de qualité/profondeur
                $table->enum('content_quality', [
                    'court',           // Articles courts, brefs
                    'moyen',           // Articles de longueur moyenne
                    'profond',         // Articles approfondis
                    'exclusif'         // Contenu exclusif de haute valeur
                ])->default('court')->after('article_type');
            }
            
            if (!Schema::hasColumn('articles', 'storage_size')) {
                // Taille du fichier document pour l'espace de stockage
                $table->bigInteger('storage_size')->nullable()->after(
                    Schema::hasColumn('articles', 'file_size') ? 'file_size' : 'document_path'
                );
            }
            
            if (!Schema::hasColumn('articles', 'download_count')) {
                // Nombre de téléchargements pour tracking
                $table->integer('download_count')->default(0)->after(
                    Schema::hasColumn('articles', 'views_count') ? 'views_count' : 'published_at'
                );
            }
            
            if (!Schema::hasColumn('articles', 'unit_price')) {
                // Prix unitaire si achat à l'unité (optionnel)
                $table->decimal('unit_price', 8, 2)->nullable()->after('download_count');
            }
            
            if (!Schema::hasColumn('articles', 'free_download_limit')) {
                // Restriction de téléchargement (nombre max par utilisateur gratuit)
                $table->integer('free_download_limit')->default(0)->after('unit_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'article_type',
                'content_quality', 
                'storage_size',
                'download_count',
                'unit_price',
                'free_download_limit'
            ]);
        });
    }
};
