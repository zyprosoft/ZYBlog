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
        Db::table('user_role')->insert([
            "role_id" => 1,
            "name" => "博主"
        ]);
        Db::table('category')->insert([
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
    }
}
