<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('users')->count() == 0) {
            DB::table('users')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Super Administrator',
                'email' => 'admin@mixtra.co.id',
                'password' => \Hash::make('password'),
                'mit_role_id' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
