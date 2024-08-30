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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('desc')->nullable();
            $table->bigInteger('creator_id');
            $table->tinyInteger('creator_type')->default(1);
            $table->timestamps();
            $table->timestamp('updated_real')->useCurrent();
            $table->timestamp('offsale_at')->nullable();
            $table->integer('stock_limit')->default('0');
            $table->bigInteger('cash')->default(0);
            $table->bigInteger('coins')->default(0);
            $table->bigInteger('sales')->default(1);
            $table->integer('type');
            $table->integer('special')->default('0');
            $table->text('source');
            $table->text('hash');
            $table->tinyInteger('pending')->default('1');
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
        Schema::dropIfExists('items');
    }
};
