<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role' => config('constants.ROLES.GENERAL'),
            'address_id' => 0,
            'username' => 'admin',
            'password' => Hash::make('admin@123456'),
        ]);
    }
}
