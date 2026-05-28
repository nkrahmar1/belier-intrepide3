<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la colonne existe déjà
        if (!Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('nom');
            });

            // Mettre à jour les slugs existants
            DB::table('categories')->orderBy('id')->chunk(100, function ($categories) {
                foreach ($categories as $category) {
                    DB::table('categories')
                        ->where('id', $category->id)
                        ->update(['slug' => \Illuminate\Support\Str::slug($category->nom)]);
                }
            });

            // Rendre la colonne non nullable une fois les slugs générés
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable(false)->change();
            });
        }
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
