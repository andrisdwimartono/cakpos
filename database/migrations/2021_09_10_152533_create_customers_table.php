<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 255);
            $table->string('photo_profile')->nullable();
            $table->integer('company_id');
            $table->string('id_card_number', 75);
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->integer('segment_level');
            $table->string('segment_level_label', 255)->nullable();
            $table->integer('member_level');
            $table->string('member_level_label', 255)->nullable();
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
        Schema::dropIfExists('customers');
    }
}
