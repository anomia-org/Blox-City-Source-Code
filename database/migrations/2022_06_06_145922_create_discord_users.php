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
        Schema::create('discord_users', function (Blueprint $table) {
            $table->bigInteger('user_id')->unique();
            $table->id();
            $table->string('username');
            $table->string('discriminator', '255');
            $table->string('avatar', '255')->nullable();
            $table->boolean('verified');
            $table->string('locale', '255');
            $table->boolean('mfa_enabled');
            $table->string('refresh_token', '255')->nullable();
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
        Schema::dropIfExists('discord_users');
    }
};
