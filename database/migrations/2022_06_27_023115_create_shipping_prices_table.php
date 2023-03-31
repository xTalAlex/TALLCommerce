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
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('price',8,2);
            $table->decimal('min_spend',8,2)->nullable();
            $table->unsignedInteger('min_days')->nullable();
            $table->unsignedInteger('max_days')->nullable();
            $table->boolean('active')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_price_id')->nullable();
            $table->decimal('shipping_price',8,2)->nullable();
            $table->date('avaiable_from')->nullable();
        
            $table->foreign('shipping_price_id')->references('id')->on('shipping_prices')
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_price_id']);
            $table->dropColumn('shipping_price_id');
        });
        Schema::dropIfExists('shipping_prices');
    }
};
