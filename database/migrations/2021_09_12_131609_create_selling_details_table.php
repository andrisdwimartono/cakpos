<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selling_details', function (Blueprint $table) {
            $table->id();
            $table->integer("parent_id")->nullable();
            $table->integer("no_seq")->nullable();
            $table->string('product_or_bundle');
            $table->string('product_or_bundle_label', 255)->nullable();
            $table->string('is_bundle', 3)->default("0");
            $table->string('product')->nullable();
            $table->string('product_label')->nullable();
            $table->string('bundle')->nullable();
            $table->string('bundle_label')->nullable();
            $table->double('selling_price', 8, 0);
            $table->double('quantity', 8, 0);
            $table->double('discount_percentage', 8, 0)->nullable();
            $table->double('discount_total', 8, 0)->nullable();
            $table->double('total', 8, 0);
            $table->integer('warehouse');
            $table->string('warehouse_label', 255)->nullable();
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
        Schema::dropIfExists('selling_details');
    }
}
