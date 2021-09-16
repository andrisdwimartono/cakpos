<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasings', function (Blueprint $table) {
            $table->id();
            $table->string('purchasing_name', 255);
            $table->integer('company_id');
            $table->dateTime('purchasing_datetime', $precision = 0);
            $table->dateTime('buying_datetime', $precision = 0);
            $table->integer('supplier')->nullable();
            $table->string('supplier_label', 255)->nullable();
            $table->double('purchasing_detail_total', 8, 0);
            $table->double('purchasing_discount_percentage', 8, 0);
            $table->double('purchasing_discount_total', 8, 0);
            $table->double('purchasing_total', 8, 0);
            $table->string('is_paynow')->nullable();
            $table->double('buying_total', 8, 0);
            $table->double('change_total', 8, 0);
            $table->string('purchasing_status')->default("Belum Lunas");
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
        Schema::dropIfExists('purchasings');
    }
}
