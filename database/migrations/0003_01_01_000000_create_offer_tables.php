<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // OfferType enum
            $table->string('title');
            $table->string('slug');
            $table->string('tagline')->nullable();
            $table->text('short_pitch')->nullable();
            $table->longText('long_copy')->nullable();
            $table->json('benefits')->nullable();
            $table->json('faq')->nullable();
            $table->string('pricing_suggestion')->nullable();
            $table->text('upsell_idea')->nullable();
            $table->string('cta_text')->nullable()->default('Buy Now');
            $table->unsignedInteger('price')->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->string('interval', 20)->nullable(); // monthly, yearly, once
            $table->unsignedInteger('credit_amount')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('mayar_product_id')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['workspace_id', 'slug']);
        });

        Schema::create('offer_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->json('content'); // full AI output snapshot
            $table->string('generated_by', 20)->default('ai'); // ai, manual
            $table->timestamps();
        });

        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('input_params');
            $table->json('output_content')->nullable();
            $table->string('model_used')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();
        });

        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->string('template')->default('default');
            $table->text('custom_css')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
        Schema::dropIfExists('ai_generations');
        Schema::dropIfExists('offer_versions');
        Schema::dropIfExists('offers');
    }
};
