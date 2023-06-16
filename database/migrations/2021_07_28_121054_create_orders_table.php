<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('market');
            $table->string('internal_order_id');
            $table->string('exchange_order_id');
            $table->string('type');
            $table->string('side');
            $table->float('original_market_price');
            $table->float('original_price');
            $table->float('stop_price');
            $table->float('executed_price');
            $table->float('original_quantity');
            $table->float('executed_quantity');
            $table->float('cumulative_quote_quantity');
            $table->float('fill_percentage');
            $table->string('status');
            $table->boolean('is_virtual');
            $table->boolean('is_active_stop_limit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
