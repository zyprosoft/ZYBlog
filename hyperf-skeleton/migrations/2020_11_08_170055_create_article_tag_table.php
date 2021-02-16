<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
 * @license  GPL
 */

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleTagTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("article_id")
                  ->comment("文章ID");
            $table->integer("tag_id")
                  ->comment("标签ID");
            $table->unique(["article_id","tag_id"]);
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
        Schema::dropIfExists('article_tag');
    }
}
