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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->date('birthday');
            $table->text('biography')->nullable();
            $table->text('signature')->nullable();
            $table->bigInteger('cash')->default('5');
            $table->bigInteger('coins')->default('10');
            $table->bigInteger('primary_guild')->nullable();
            $table->text('last_currency')->nullable();
            $table->timestamp('last_online')->useCurrent();
            $table->tinyInteger('membership')->default('0');
            $table->timestamp('membership_expires')->nullable();
            $table->integer('theme')->default('1');
            $table->tinyInteger('power')->default('0');
            $table->tinyInteger('deleted')->default('0');
            $table->timestamps();
            $table->timestamp('flood_gate')->useCurrent();
            $table->timestamp('action_flood_gate')->useCurrent();
            $table->text('avatar_url');
            $table->timestamp('avatar_render')->useCurrent();
            $table->text('headshot_url');
            $table->timestamp('headshot_render')->useCurrent();
            $table->bigInteger('views')->default(0);
            $table->string('ais')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
