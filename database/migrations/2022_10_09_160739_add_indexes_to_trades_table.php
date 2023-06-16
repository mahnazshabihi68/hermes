<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->index('internal_trade_id');
            $table->index('exchange_trade_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->dropIndex(['internal_trade_id']);
            $table->dropIndex(['exchange_trade_id']);
            $table->dropIndex(['order_id']);
        });
    }
};
