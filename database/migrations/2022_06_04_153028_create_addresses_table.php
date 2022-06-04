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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('full_name')->nullable();
            $table->string('company')->nullable();
            $table->string('address');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('province')->nullable();
            $table->string('country_region');
            $table->string('postal_code');
            $table->boolean('billing')->default(0);
            $table->boolean('default')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
