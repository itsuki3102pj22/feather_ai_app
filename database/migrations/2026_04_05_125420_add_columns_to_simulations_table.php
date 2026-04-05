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
        Schema::table('simulations', function (Blueprint $table) {
            $table->decimal('down_ratio', 5, 1)->default(85)->after('origin'); // ダウン率（％）
            $table->decimal('profit_rate', 5, 1)->default(10.0)->after('feather_jpy'); // 利益率（％）
            $table->decimal('sale_price_jpy', 10, 2)->nullable()->after('profit_rate'); // 販売価格（円）
            $table->string('customer_name')->nullable()->after('sale_price_jpy'); // 顧客名
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->dropColumn(['down_ratio', 'profit_rate', 'sale_price_jpy', 'customer_name']);
        });
    }
};
