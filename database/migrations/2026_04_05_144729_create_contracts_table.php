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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('season');                      // 例：2025-2026
            $table->string('feather_type');                // ホワイトダック / グレーダック
            $table->string('origin');                      // 産地
            $table->decimal('down_ratio', 5, 1);           // ダウン比率
            $table->decimal('contract_kg', 10, 2);         // 契約数量（kg）
            $table->decimal('shipped_kg', 10, 2)->default(0); // 出荷済数量（kg）
            $table->decimal('unit_price_jpy', 10, 2)->nullable(); // 契約単価（円/kg）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
