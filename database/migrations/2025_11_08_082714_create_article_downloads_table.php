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
        Schema::create('article_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->string('download_type')->default('free'); // 'free', 'premium', 'purchase'
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->decimal('cost', 8, 2)->default(0); // Coût si achat unitaire
            $table->timestamps();
            
            // Index pour les requêtes fréquentes
            $table->index(['user_id', 'article_id']);
            $table->index('download_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_downloads');
    }
};
