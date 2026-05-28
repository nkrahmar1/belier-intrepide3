<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // info, warning, error, critical
            $table->string('category'); // system, security, performance, etc.
            $table->string('title');
            $table->text('message');
            $table->json('context')->nullable(); // données contextuelles
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['level', 'category', 'created_at']);
            $table->index(['is_read', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
};
