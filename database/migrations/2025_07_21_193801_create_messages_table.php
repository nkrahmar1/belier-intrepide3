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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de l'expéditeur
            $table->string('email'); // Email de l'expéditeur
            $table->string('subject')->nullable(); // Sujet du message
            $table->text('content'); // Contenu du message
            $table->boolean('is_read')->default(false); // Message lu ou non
            $table->enum('status', ['nouveau', 'lu', 'traité', 'archivé'])->default('nouveau'); // Statut du message
            $table->string('phone')->nullable(); // Téléphone (optionnel)
            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};