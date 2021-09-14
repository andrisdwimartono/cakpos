<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('bundle_name', 255);
            $table->integer('company_id');
            $table->string('bundle_code', 255);
            $table->double('total_price', 8, 0);
            $table->double('discount_percentage_bundle', 8, 0);
            $table->double('discount_total_bundle', 8, 0);
            $table->double('total_bundle', 8, 0);
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
        Schema::dropIfExists('bundles');
    }
}
