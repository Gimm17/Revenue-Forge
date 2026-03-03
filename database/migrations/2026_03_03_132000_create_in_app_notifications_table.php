<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50); // new_order, payment_failed, subscription_cancelled
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('icon', 10)->default('💰');
            $table->json('data')->nullable(); // order_id, offer_title, amount, etc.
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_app_notifications');
    }
};
