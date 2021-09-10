<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string('product_name', 255);
            $table->string('product_photo')->nullable();
            $table->integer('company_id');
            $table->string('produce_code', 255)->nullable();
            $table->integer('uom')->nullable();
            $table->string('uom_label', 255)->nullable();
            $table->integer('category')->nullable();
            $table->string('category_label', 255)->nullable();
            $table->double('buying_price', 8, 0)->default(0);
            $table->double('selling_price', 8, 0)->default(0);
            $table->double('discount_percentage', 8, 0)->default(0);
            $table->double('discount', 8, 0)->default(0);
            $table->enum('status', ['Tersedia', 'Tidak Tersedia'])->default('tersedia');
            $table->string('status_label', 255)->nullable();
            $table->integer('user_creator_id')->nullable();
            $table->integer('user_updater_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
