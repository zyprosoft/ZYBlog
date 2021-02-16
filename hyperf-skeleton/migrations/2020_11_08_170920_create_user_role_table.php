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

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->integerIncrements('role_id');
            $table->string("name",20)
                  ->comment("角色名");
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
        Schema::dropIfExists('user_role');
    }
}
