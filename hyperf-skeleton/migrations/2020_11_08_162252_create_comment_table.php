<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->bigIncrements('comment_id')
                  ->comment("评论ID");
            $table->string("content",128)
                  ->comment("评论内容");
            $table->bigIncrements('parent_comment_id')
                  ->nullable()
                  ->comment("父评论ID");
            $table->integer('article_id')
                  ->comment("文章ID");
            $table->integer('user_id')
                  ->comment("作者ID");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
}
