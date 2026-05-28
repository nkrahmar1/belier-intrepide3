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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'subscription_type')) {
                $table->string('subscription_type')->nullable()->after('is_premium');
            }
            if (!Schema::hasColumn('users', 'subscription_expires_at')) {
                $table->timestamp('subscription_expires_at')->nullable()->after('subscription_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_premium')) {
                $table->dropColumn('is_premium');
            }
            if (Schema::hasColumn('users', 'subscription_type')) {
                $table->dropColumn('subscription_type');
            }
            if (Schema::hasColumn('users', 'subscription_expires_at')) {
                $table->dropColumn('subscription_expires_at');
            }
        });
    }
};
