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
        Schema::create('guilds_announcements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('guild_id');
            $table->bigInteger('user_id');
            $table->string('title')->default("Announcement");
            $table->text('body');
            $table->boolean('scrubbed')->default(false);
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
        Schema::dropIfExists('guilds_announcements');
    }
};
