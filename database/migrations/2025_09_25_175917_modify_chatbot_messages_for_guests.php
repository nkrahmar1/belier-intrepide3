<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip modification pour SQLite - la table sera recréée si nécessaire
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('chatbot_messages', function (Blueprint $table) {
                // Supprimer la contrainte de clé étrangère existante
                $table->dropForeign(['user_id']);

                // Modifier la colonne user_id pour accepter des strings
                $table->string('user_id')->change();

                // Modifier l'enum type pour inclure 'admin' au lieu de 'bot'
                DB::statement("ALTER TABLE chatbot_messages MODIFY COLUMN type ENUM('user', 'admin') DEFAULT 'user'");

                // Ajouter un index pour les performances
                $table->index(['user_id', 'type', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip pour SQLite
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('chatbot_messages', function (Blueprint $table) {
                // Reconvertir user_id en integer
                $table->unsignedBigInteger('user_id')->change();

                // Rétablir la contrainte de clé étrangère
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                // Rétablir l'enum original
                DB::statement("ALTER TABLE chatbot_messages MODIFY COLUMN type ENUM('user', 'bot') DEFAULT 'user'");

                // Supprimer l'index ajouté
                $table->dropIndex(['user_id', 'type', 'created_at']);
            });
        }
    }
};
