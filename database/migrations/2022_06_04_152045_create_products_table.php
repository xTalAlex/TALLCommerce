<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('original_price',8,2);
            $table->decimal('selling_price',8,2)->nullable();
            $table->boolean('discount_is_fixed_amount')->nullable();
            $table->decimal('discount_amount',6,2)->nullable();
            $table->decimal('tax_rate',4,2)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('hidden')->default(false);
            //$table->decimal('weight',8,2)->nullable();
            $table->integer('weight')->nullable();
            $table->date('avaiable_from')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
    }
};
