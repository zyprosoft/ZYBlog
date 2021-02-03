<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;

class CreateUserRoleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Db::table('user_role')->insertOrIgnore([
            "role_id" => 1,
            "name" => "博主"
        ]);
        Db::table('user')->insertOrIgnore([
            'role_id' => 1,
            'username' => 'admin',
            'password' => password_hash('admin123',PASSWORD_DEFAULT),
            'nickname' => 'admin',
            'email' => '1003081775@qq.com'
        ]);
        Db::table('about')->insertOrIgnore([
            'email' => '1003081775@qq.com',
            'nickname' => 'admin',
            'username' => 'admin',
        ]);
        Db::table('category')->insertOrIgnore([
            [
                "name" => "经验分享"
            ],
            [
                "name" => "生活随笔"
            ],
            [
                "name" => "转载收藏"
            ]
        ]);
        Db::table('about')->insertOrIgnore([
            [
                'blog_name' => 'ZYProSoft',
                'qq' => '1003081775',
                'wx' => 'zyprosoft',
                'sex' => 1,
                'birthday' => '1990-12-08',
                'github' => 'http://github.com/zyprosoft',
                'introduce' => '寻找一盏能够指引我前进的明灯，在黑暗又孤寂的夜晚，不再思绪乱飞，踌躇和彷徨。'
            ]
        ]);
    }
}
