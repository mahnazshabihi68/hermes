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
        Schema::table('markets', function (Blueprint $table) {
            $table->index('market');
            $table->index('is_active');
            $table->index('is_internal');
            $table->index('is_direct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->dropIndex(['market']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_internal']);
            $table->dropIndex(['is_direct']);
        });
    }
};
