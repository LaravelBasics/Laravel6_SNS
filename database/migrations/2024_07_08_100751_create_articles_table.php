<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title');
            $table->text('body');
            $table->bigInteger('user_id');//pgsql教材のコード
            // $table->unsignedBigInteger('user_id'); // mysql修正: 外部キーに適切な型を使用
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        // 外部キー制約では、参照先の主キーと同じ型を使用することが推奨されます。
        // 通常、主キーは符号なし整数 (unsignedBigInteger) であることが多いため、外部キーも同じ型を使用するのが良いプラクティスです。
        // これにより、データの整合性が保たれ、データベースのパフォーマンスも向上します。
        // したがって、Laravelでは外部キーとしてカラムを定義する際には、unsignedBigInteger() メソッドを使うことが推奨されています
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
