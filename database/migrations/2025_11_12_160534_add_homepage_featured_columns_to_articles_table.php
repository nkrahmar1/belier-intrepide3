<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('featured_on_homepage')->default(false)->after('is_published');
            $table->timestamp('homepage_featured_at')->nullable()->after('featured_on_homepage');
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['featured_on_homepage', 'homepage_featured_at']);
        });
    }
};
