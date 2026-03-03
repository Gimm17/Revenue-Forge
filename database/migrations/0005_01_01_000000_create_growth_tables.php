<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('commission_type', 20)->default('percentage');
            $table->unsignedInteger('commission_value')->default(10);
            $table->unsignedInteger('cookie_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('affiliate_programs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained('affiliate_links')->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamp('clicked_at');
        });

        Schema::create('affiliate_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained('affiliate_links')->cascadeOnDelete();
            $table->foreignId('click_id')->nullable()->constrained('affiliate_clicks')->nullOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('commission_amount')->default(0);
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->json('event_data')->nullable();
            $table->timestamp('created_at');
        });

        Schema::create('daily_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('gross_revenue')->default(0);
            $table->unsignedInteger('paid_orders')->default(0);
            $table->unsignedInteger('new_customers')->default(0);
            $table->unsignedInteger('active_subscriptions')->default(0);
            $table->unsignedInteger('credits_sold')->default(0);
            $table->unsignedInteger('affiliate_revenue')->default(0);
            $table->timestamps();
            $table->unique(['workspace_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_metrics');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('affiliate_conversions');
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('affiliate_links');
        Schema::dropIfExists('affiliate_programs');
    }
};
