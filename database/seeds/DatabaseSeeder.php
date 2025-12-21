<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'MasterAdmin',
            'role' => 'master',
            'email' => 'masteradmin@gmail.com',
            'password' => bcrypt('123456789'),
            'userid' => date('Ymd') . '0001',
            'usercode' => 'MasterAdmin',
            'systemname' => 'MasterAdmin',
            'systemcode' => 'MasterAdmin',
            'last_login' => date('Y-m-d h:m:s'),
            'created_at' => date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
            'status' => 1
        ]);
    }
}
