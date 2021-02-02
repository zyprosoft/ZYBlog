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
            $table->string('nickname', 30)->nullable();
            $table->string('username', 30)->nullable();
            $table->string('email', 50);
            $table->string('qq', 30)->nullable();
            $table->string('wx', 30)->nullable();
            $table->string('wb', 30)->nullable();
            $table->string('introduce', 500)->nullable();
            $table->string('github', 100)->nullable();
            $table->string('birthday', 20)->nullable();
            $table->string('mobile', 11)->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('blog_name', 30)->nullable();
            $table->string('job', 30)->nullable();
            $table->string('hobby', 128)->nullable();
            $table->string('work_history', 500)->nullable();
            $table->string('company', 50)->nullable();
            $table->string('music',500)->nullable();
            $table->tinyInteger('sex')->nullable();
            $table->string('constellation', 5)->nullable();
            $table->string('icp', 30)->nullable();
            $table->string('qq_code', 128)->nullable();
            $table->string('wx_code', 128)->nullable();
            $table->unique('username');
            $table->unique('email');
            $table->unique('mobile');
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
