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
        Schema::table('gateways', function (Blueprint $table) {
            $table->string('nomad_uri')->nullable()->after('bspay_cliente_secret');
            $table->string('nomad_secret')->nullable()->after('nomad_uri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gateways', function (Blueprint $table) {
            $table->dropColumn(['nomad_uri', 'nomad_secret']);
        });
    }
};
