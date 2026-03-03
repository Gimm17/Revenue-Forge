<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->string('secondary_color', 7)->nullable()->after('brand_color');
            $table->string('font_family', 50)->nullable()->after('secondary_color');
            $table->boolean('chat_widget_enabled')->default(false)->after('font_family');
            $table->string('chat_greeting')->nullable()->after('chat_widget_enabled');
            $table->string('chat_email')->nullable()->after('chat_greeting');
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropColumn(['secondary_color', 'font_family', 'chat_widget_enabled', 'chat_greeting', 'chat_email']);
        });
    }
};
