<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->integerIncrements('article_id')
                  ->comment("文章ID");
            $table->string("title", 50)
                  ->comment("标题");
            $table->text("content")
                  ->comment("文章内容");
            $table->integer("user_id")
                  ->comment("作者ID");
            $table->integer("category_id")
                  ->default(0)
                  ->comment("分类ID");
            $table->integer("read_count")
                  ->default(0)
                  ->comment("阅读数");
            $table->integer("comment_count")
                  ->default(0)
                  ->comment("评论数");
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('article');
    }
}
