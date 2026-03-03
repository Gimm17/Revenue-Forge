<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('delivery_type', 30)->nullable()->after('cover_image_url'); // none, file_url, text_content, redirect_url
            $table->text('delivery_content')->nullable()->after('delivery_type'); // URL, text, or rich content
            $table->string('delivery_label')->nullable()->after('delivery_content'); // e.g. "Download Your Ebook"
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'delivery_content', 'delivery_label']);
        });
    }
};
