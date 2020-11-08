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
        Db::table('user_role')->updateOrInsert(["role_id","name"],[["1","管理员"]]);
        Db::table('category')->updateOrInsert(["name"],["经验分享","生活随笔","转载收藏"]);
    }
}
