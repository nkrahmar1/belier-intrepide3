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
            // Informations sur le document uploadé
            $table->string('file_original_name')->nullable()->after('document_path');
            $table->string('file_mime_type')->nullable()->after('file_original_name');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_mime_type'); // en bytes
            
            // Support pour plusieurs images (galerie)
            $table->json('gallery_images')->nullable()->after('image');
            
            // Métadonnées supplémentaires
            $table->string('image_alt')->nullable()->after('gallery_images');
            $table->text('meta_description')->nullable()->after('image_alt');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'file_original_name',
                'file_mime_type',
                'file_size',
                'gallery_images',
                'image_alt',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }
};
