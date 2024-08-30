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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('by');
            $table->bigInteger('uid');
            $table->bigInteger('rid');
            $table->text('rule')->nullable();
            $table->tinyInteger('type');
            $table->tinyInteger('active')->default('1');
            $table->bigInteger('admin_id')->nullable();
            $table->bigInteger('action_id')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
