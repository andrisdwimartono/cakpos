<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellings', function (Blueprint $table) {
            $table->id();
            $table->string('selling_name', 255);
            $table->integer('company_id');
            $table->dateTime('selling_datetime', $precision = 0);
            $table->dateTime('paying_datetime', $precision = 0);
            $table->integer('customer')->nullable();
            $table->string('customer_label', 255)->nullable();
            $table->double('selling_detail_total', 8, 0);
            $table->double('selling_discount_percentage', 8, 0);
            $table->double('selling_discount_total', 8, 0);
            $table->double('selling_total', 8, 0);
            $table->string('is_paynow')->nullable();
            $table->double('paying_total', 8, 0);
            $table->double('change_total', 8, 0);
            $table->string('selling_status')->default("Belum Lunas");
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
        Schema::dropIfExists('sellings');
    }
}
