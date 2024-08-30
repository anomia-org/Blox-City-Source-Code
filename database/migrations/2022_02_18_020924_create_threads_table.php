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
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('topic_id');
            $table->text('title');
            $table->longText('body');
            $table->timestamp('last_reply')->useCurrent();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('views')->default('0');
            $table->tinyInteger('scrubbed')->default('0');
            $table->tinyInteger('pinned')->default('0');
            $table->tinyInteger('locked')->default('0');
            $table->tinyInteger('stuck')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
};
