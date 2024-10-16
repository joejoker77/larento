<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->string('settings')->primary(true);
            $table->string('name')->nullable()->default('Larento');
            $table->string('address')->nullable()->default('Москва, Бирюлевская улица, 43');
            $table->string('work_time')->nullable()->default('Пн-Вс: 10:00 - 20:00');
            $table->string('slogan')->nullable()->default('Производство современной мебели в России');
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();
            $table->integer('default_pagination')->nullable()->default(12);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
