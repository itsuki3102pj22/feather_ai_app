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
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->string('feather_type'); //羽毛種
            $table->string('origin'); //産地
            $table->decimal('feather_usd', 8, 2); //羽毛単価(ドル）
            $table->decimal('usd_jpy', 8, 2); //ドル価格(円/ドル)
            $table->decimal('feather_jpy', 8, 2); //羽毛単価(円)
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
