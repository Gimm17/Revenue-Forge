<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('mayar_customer_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['workspace_id', 'email']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30)->default('offer_purchase');
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('amount')->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->string('mayar_checkout_url', 500)->nullable();
            $table->string('mayar_transaction_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['workspace_id', 'status']);
            $table->index('mayar_transaction_id');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('amount')->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->string('mayar_payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->string('event_key')->unique();
            $table->json('payload');
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('active');
            $table->string('mayar_subscription_id')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('active');
            $table->timestamp('granted_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('portal_sessions_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('magic_link');
            $table->string('status', 20)->default('sent');
            $table->timestamp('sent_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('credit_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->integer('balance')->default(0);
            $table->timestamps();
            $table->unique(['workspace_id', 'customer_id']);
        });

        Schema::create('credit_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('credit_wallets')->cascadeOnDelete();
            $table->string('type', 10); // credit, debit
            $table->integer('amount');
            $table->integer('balance_after');
            $table->string('description');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });

        Schema::create('mayar_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('mayar_id');
            $table->string('name');
            $table->string('type', 30);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('mayar_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('mayar_id');
            $table->string('email');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('mayar_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');
            $table->string('event_id')->nullable();
            $table->json('payload');
            $table->string('signature')->nullable();
            $table->string('status', 20)->default('received');
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mayar_webhook_logs');
        Schema::dropIfExists('mayar_customers');
        Schema::dropIfExists('mayar_products');
        Schema::dropIfExists('credit_ledgers');
        Schema::dropIfExists('credit_wallets');
        Schema::dropIfExists('portal_sessions_log');
        Schema::dropIfExists('customer_access');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
    }
};
