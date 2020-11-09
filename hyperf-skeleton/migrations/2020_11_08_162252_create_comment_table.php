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
            $table->bigInteger('parent_comment_id')
                  ->nullable()
                  ->comment("父评论ID");
            $table->integer('article_id')
                  ->comment("文章ID");
            $table->integer('user_id')
                  ->comment("作者ID");
            $table->timestamps();
            $table->engine = "InnoDB";
            $table->charset = "utf8mb4";
            $table->collation = "utf8mb4_unicode_ci";
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
