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
        Schema::create('shop_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('status');
            $table->string('user_name');
            $table->text('comment');
            $table->tinyInteger('vote');
            $table->timestamps();
            $table->foreign('product_id')
                ->references('id')
                ->on('shop_products')
                ->onDelete('CASCADE')
                ->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_comments');
    }
};
