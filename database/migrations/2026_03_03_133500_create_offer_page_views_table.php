<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Page views tracking for offers
        Schema::create('offer_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_id', 64); // hashed IP + UA for unique visitor tracking
            $table->string('referrer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('device_type', 20)->nullable(); // mobile, desktop, tablet
            $table->timestamp('viewed_at');

            $table->index(['offer_id', 'viewed_at']);
            $table->index(['workspace_id', 'viewed_at']);
        });

        // Add page_views and conversion_rate tracking to daily_metrics
        Schema::table('daily_metrics', function (Blueprint $table) {
            $table->unsignedInteger('page_views')->default(0)->after('affiliate_revenue');
            $table->unsignedInteger('checkouts_started')->default(0)->after('page_views');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_page_views');
        Schema::table('daily_metrics', function (Blueprint $table) {
            $table->dropColumn(['page_views', 'checkouts_started']);
        });
    }
};
