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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("creator_id");
            $table->bigInteger("item_id");
            $table->string("image_path");
            $table->tinyInteger("pending")->default(1);
            $table->timestamps();
            $table->timestamp('bid_at')->nullable();
            $table->bigInteger("bid");
            $table->bigInteger("total_bids");
            $table->bigInteger("total_clicks")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
};
