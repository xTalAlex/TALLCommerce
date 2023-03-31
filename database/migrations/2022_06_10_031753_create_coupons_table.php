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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('is_fixed_amount');
            $table->decimal('amount',6,2);
            $table->unsignedInteger('redemptions')->default(0);
            $table->unsignedInteger('max_redemptions')->nullable();
            $table->decimal('min_total',8,2)->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->boolean('once_per_user')->default(false);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount',8,2)->nullable();

            $table->foreign('coupon_id')->references('id')->on('coupons')
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
            $table->dropForeign(['coupon_id']);
            $table->dropColumn('coupon_id');
        });
        Schema::dropIfExists('coupons');
    }
};
