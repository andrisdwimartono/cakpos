<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name', 255);
            $table->integer('company_id');
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->string('supplier_company', 255)->nullable();
            $table->string('email')->nullable();
            $table->string('phone_1', 20);
            $table->string('phone_2', 20)->nullable();
            $table->string('photo_profile')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
