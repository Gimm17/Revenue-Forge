<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('midtrans_snap_token', 500)->nullable()->after('mayar_transaction_id');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_snap_token');
            $table->index('midtrans_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['midtrans_transaction_id']);
            $table->dropColumn(['midtrans_snap_token', 'midtrans_transaction_id']);
        });
    }
};
