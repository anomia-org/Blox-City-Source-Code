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
        Schema::create('guilds_ranks', function (Blueprint $table) {
            $table->id(); 
            $table->bigInteger('guild_id');
            $table->string('name'); 
            $table->integer('rank');
            $table->boolean('can_view_wall')->default(true);
            $table->boolean('can_post_on_wall')->default(true);
            $table->boolean('can_moderate_wall')->default(false);
            $table->boolean('can_view_audit')->default(false);
            $table->boolean('can_advertise')->default(false);
            $table->boolean('can_change_ranks')->default(false);
            $table->boolean('can_kick_members')->default(false);
            $table->boolean('can_accept_members')->default(false);
            $table->boolean('can_post_announcements')->default(false);
            $table->boolean('can_spend_funds')->default(false);
            $table->boolean('can_create_items')->default(false);
            $table->boolean('can_edit_games')->default(false);
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
        Schema::dropIfExists('guilds_ranks');
    }
};
