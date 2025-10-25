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
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('price');
            }
            if (!Schema::hasColumn('subscriptions', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('subscriptions', 'plan_id')) {
                $table->integer('plan_id')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            if (Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('subscriptions', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('subscriptions', 'plan_id')) {
                $table->dropColumn('plan_id');
            }
        });
    }
};
