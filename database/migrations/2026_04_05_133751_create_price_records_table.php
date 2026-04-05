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
        Schema::create('price_records', function (Blueprint $table) {
            $table->id();
            $table->date('record_date');// 記録日
            $table->string('period_type')->default('monthly'); //　monthly, weekly
            $table->decimal('usd_jpy', 8, 2);// 為替レート
            $table->decimal('white_duck_usd', 10 , 2);// ホワイトダックのドル価
            $table->decimal('white_duck_jpy', 10 , 2);// ホワイトダックの円価
            $table->decimal('grey_duck_jpy', 10, 2);// グレーダックの円価価
            $table->text('ai_comment')->nullable();// AIコメント
            $table->text('manual_comment')->nullable();// 手動コメント
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_records');
    }
};
