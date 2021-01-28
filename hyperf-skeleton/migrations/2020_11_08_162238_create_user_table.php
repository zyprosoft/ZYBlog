<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integerIncrements('user_id');
            $table->string("nickname", 50)
                  ->comment("用户昵称");
            $table->string("username", 50)
                  ->nullable()
                  ->comment("账号");
            $table->string("password", 255)
                  ->nullable()
                  ->comment("密码");
            $table->string("email", 50)
                  ->comment("邮箱");
            $table->string("site", 120)
                  ->nullable()
                  ->comment("个人站点");
            $table->string("avatar", 128)
                  ->nullable()
                  ->comment("头像");
            $table->integer("role_id")
                  ->default(0)
                  ->comment("用户角色ID");
            $table->string("introduce", 128)
                  ->default('这个人什么太懒了，没有自我介绍')
                  ->comment("自我介绍");
            $table->unique('username');
            $table->unique('email');

            $table->softDeletes();
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
        Schema::dropIfExists('user');
    }
}
