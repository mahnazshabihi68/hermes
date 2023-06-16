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
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('market');
            $table->index('internal_order_id');
            $table->index('exchange_order_id');
            $table->index('type');
            $table->index('side');
            $table->index('status');
            $table->index('is_virtual');
            $table->index('is_active_stop_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['market']);
            $table->dropIndex(['internal_order_id']);
            $table->dropIndex(['exchange_order_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['side']);
            $table->dropIndex(['status']);
            $table->dropIndex(['is_virtual']);
            $table->dropIndex(['is_active_stop_limit']);
        });
    }
};
