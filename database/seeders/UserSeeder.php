<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [[
            "id" => 1,
            "full_name" => "Fathimath Shahina",
            "username" => "admin",
            "email" => "noreplyatshah@gamil.com",
            "user_type" => "admin",
            "password" => bcrypt('$admin123$')
        ]];

        DB::table('users')->insert($arr);

    }
}
