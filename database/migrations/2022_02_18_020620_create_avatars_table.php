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
        Schema::create('avatars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('tshirt_id')->nullable();
            $table->bigInteger('shirt_id')->nullable();
            $table->bigInteger('pants_id')->nullable();
            $table->bigInteger('hat1_id')->nullable();
            $table->bigInteger('hat2_id')->nullable();
            $table->bigInteger('hat3_id')->nullable();
            $table->bigInteger('face_id')->nullable();
            $table->bigInteger('tool_id')->nullable();
            $table->char('hex_head')->default('FAF123');
            $table->char('hex_torso')->default('FAF123');
            $table->char('hex_larm')->default('FAF123');
            $table->char('hex_rarm')->default('FAF123');
            $table->char('hex_lleg')->default('FAF123');
            $table->char('hex_rleg')->default('FAF123');
            $table->tinyInteger('avatar')->default(1);
            $table->tinyInteger('orient')->default(1);
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
        Schema::dropIfExists('avatars');
    }
};
