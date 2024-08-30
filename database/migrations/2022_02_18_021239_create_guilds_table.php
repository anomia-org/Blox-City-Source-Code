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
        Schema::create('guilds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id')->unsigned();
            $table->string('name')->unique();
            $table->text('desc')->nullable();
            $table->bigInteger('cash')->default(0);
            $table->bigInteger('coins')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_private')->default(false);
            $table->boolean('is_vault_viewable')->default(true);
            $table->boolean('is_games_viewable')->default(true);
            $table->boolean('is_accepting_affiliates')->default(true);
            $table->boolean('is_locked')->default(false);
            $table->string('thumbnail_url');
            $table->boolean('is_thumbnail_pending')->default(true);
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
        Schema::dropIfExists('guilds');
    }
};
