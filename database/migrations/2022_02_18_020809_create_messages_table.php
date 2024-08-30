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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from')->nullable();
            $table->bigInteger('to')->nullable();
            $table->text('subject');
            $table->longText('body');
            $table->timestamps();
            $table->bigInteger('reply_to')->nullable();
            $table->tinyInteger('read')->default('0');
            $table->tinyInteger('archived')->default('0');
            $table->tinyInteger('scrubbed')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
