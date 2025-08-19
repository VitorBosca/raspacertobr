<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('game_items')->nullable()->after('hash');
            $table->boolean('has_won')->default(false)->after('game_items');
            $table->json('winning_prize')->nullable()->after('has_won');
            $table->decimal('prize_amount', 10, 2)->default(0)->after('winning_prize');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['game_items', 'has_won', 'winning_prize', 'prize_amount']);
        });
    }
};
