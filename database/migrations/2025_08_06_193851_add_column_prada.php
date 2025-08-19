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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('pradapay_is_enable')->default(false)->after('sharkpay_is_enable')->nullable();
        });

        Schema::table('gateways', function (Blueprint $table) {
            $table->string('pradapay_uri')->default('')->after('shark_private_key')->nullable();
            $table->string('pradapay_apikey')->default('')->after('pradapay_uri')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('pradapay_is_enable');
        });

        Schema::table('gateways', function (Blueprint $table) {
            $table->dropColumn('pradapay_uri');
            $table->dropColumn('pradapay_apikey');
        });
    }
};
