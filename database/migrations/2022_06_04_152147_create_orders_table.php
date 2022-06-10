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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('shipping_address');
            $table->json('billing_address');
            $table->text('message')->nullable();
            $table->text('tracking_number')->nullable();
            $table->decimal('total',8,2)->default(0);
            $table->string('payment_type');
            $table->string('payment_id')->nullable();
            $table->unsignedBigInteger('order_status_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->foreign('order_status_id')->references('id')->on('order_statuses')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('price',6,2);
            $table->unsignedInteger('quantity');
            $table->decimal('total',8,2)->storedAs('price * quantity');

            $table->foreign('order_id')->references('id')->on('orders')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_statuses');
    }
};
