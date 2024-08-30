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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('item_id');
            $table->tinyInteger('type');
            $table->string('collection_number')->nullable()->unique();
            $table->tinyInteger('special')->default(0);
            $table->tinyInteger('can_trade')->default('0');
            $table->tinyInteger('can_open')->default('0');
            $table->integer('crate_id')->nullable();
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
        Schema::dropIfExists('inventories');
    }
};
