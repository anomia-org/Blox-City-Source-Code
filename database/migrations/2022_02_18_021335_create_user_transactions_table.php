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
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('source_id');
            $table->bigInteger('source_user');
            $table->integer('source_type');
            $table->bigInteger('cash')->nullable();
            $table->bigInteger('coins')->nullable();
            $table->integer('type');
            $table->timestamps();
            $table->timestamp('release_at')->nullable();
            $table->tinyInteger('released')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_transactions');
    }
};
