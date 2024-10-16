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
        Schema::create('shop_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('photo_id')->nullable()->default(null);
            $table->string('name');
            $table->string('slug');
            $table->string('title');
            $table->timestamp('expiration_start')->nullable();
            $table->timestamp('expiration_end')->nullable();
            $table->json('settings')->nullable();
            $table->text('description')->nullable();
            $table->text('description_two')->nullable();
            $table->text('description_three')->nullable();
            $table->json('meta')->nullable();
            $table->foreign('photo_id')
                ->references('id')
                ->on('shop_photos')
                ->onDelete('set null')
                ->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_promotions');
    }
};
