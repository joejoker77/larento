<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug');
            $table->string('status');
            $table->string('sku')->index();
            $table->text('description')->fulltext()->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->foreign('category_id')
                ->references('id')
                ->on('shop_categories')
                ->onDelete('CASCADE')
                ->onUpdate('RESTRICT');
        });

        Schema::create('shop_category_product', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->constrained('shop_categories')->onDelete('CASCADE')->onUpdate('RESTRICT');
            $table->foreignId('product_id')
                ->constrained('shop_products')->onDelete('CASCADE')->onUpdate('RESTRICT');
        });

        Schema::create('shop_product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained('shop_products')->onDelete('CASCADE')->onUpdate('RESTRICT');
            $table->foreignId('tag_id')
                ->constrained('shop_tags')->onDelete('CASCADE')->onUpdate('RESTRICT');
        });

        Schema::create('shop_related', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained('shop_products')->onDelete('CASCADE')->onUpdate('RESTRICT');
            $table->foreignId('related_id')
                ->constrained('shop_products')->onDelete('CASCADE')->onUpdate('RESTRICT');
        });

        Schema::create('shop_values', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('shop_products')
                ->onDelete('CASCADE')
                ->onUpdate('RESTRICT');
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')
                ->references('id')
                ->on('shop_attributes')
                ->onDelete('CASCADE')
                ->onUpdate('RESTRICT');
            $table->text('value')->fulltext();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_values');
        Schema::dropIfExists('shop_product_related');
        Schema::dropIfExists('shop_product_tag');
        Schema::dropIfExists('shop_category_product');
        Schema::dropIfExists('shop_products');
    }
};
