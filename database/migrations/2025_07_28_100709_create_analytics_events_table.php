<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // click, view, purchase, etc.
            $table->string('category'); // product, page, button, etc.
            $table->string('label')->nullable(); // nom du produit, page, etc.
            $table->decimal('value', 10, 2)->nullable(); // valeur monétaire ou numérique
            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->json('custom_data')->nullable(); // données personnalisées
            $table->timestamps();
            
            $table->index(['action', 'category', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics_events');
    }
};