<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAboutTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('about', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname', 30);
            $table->string('username', 30);
            $table->string('email', 50);
            $table->string('qq', 30);
            $table->string('wx', 30);
            $table->string('wb', 30);
            $table->string('introduce', 500);
            $table->string('github', 100);
            $table->string('birthday', 20);
            $table->string('mobile', 11);
            $table->string('facebook', 100);
            $table->string('twitter', 100);
            $table->string('avatar', 300);
            $table->string('blog_name', 30);
            $table->tinyInteger('sex');
            $table->string('constellation', 5);
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
        Schema::dropIfExists('about');
    }
}
