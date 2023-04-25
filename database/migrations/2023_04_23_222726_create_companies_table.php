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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->string('name')->nullable();
            $table->text('catchphrase')->nullable();
            $table->string('image')->nullable();
            $table->text('strength_image1')->nullable();
            $table->text('strength_text1')->nullable();
            $table->text('strength_image2')->nullable();
            $table->text('strength_text2')->nullable();
            $table->text('strength_image3')->nullable();
            $table->text('strength_text3')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('business_hours')->nullable();
            $table->string('holiday')->nullable();
            $table->boolean('parking_available')->default(false);
            $table->integer('parking_slots')->nullable();
            $table->string('payment_methods')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
