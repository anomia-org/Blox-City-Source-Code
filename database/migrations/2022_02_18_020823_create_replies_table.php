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
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('topic_id');
            $table->bigInteger('thread_id');
            $table->bigInteger('quote_id')->nullable();
            $table->tinyInteger('quote_type')->nullable();
            $table->longText('body');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('replies');
    }
};
