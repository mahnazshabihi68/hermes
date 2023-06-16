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
        Schema::connection('mysql')->create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('loggable_type')->nullable();
            $table->integer('loggable_id')->nullable();
            $table->string('channel')->nullable();
            $table->string('level')->nullable();
            $table->longText('data')->nullable();
            $table->string('ip')->nullable();
            $table->string('device')->nullable();
            $table->string('os')->nullable();
            $table->string('browser')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('logs');
    }
};
