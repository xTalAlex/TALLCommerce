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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->unsignedBigInteger('collection_id')->before('created_at')->nullable();

            $table->foreign('brand_id')->references('id')->on('brands')
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropColumn('collection_id');
        });
        Schema::dropIfExists('collections');
    }
};
