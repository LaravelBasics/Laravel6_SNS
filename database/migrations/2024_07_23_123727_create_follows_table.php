<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedBigInteger('follower_id'); // ここで定義
            $table->bigInteger('follower_id'); // ここで定義

            // $table->unsignedBigInteger('followee_id'); // ここで定義
            $table->bigInteger('followee_id'); // ここで定義

            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('follower_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                
            $table->foreign('followee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
