<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // Configuration Awal
            [
                'id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Logs',
                'slug' => 'logs',
                'is_default' => true,
                'controller' => 'LogController',
            ],
            [
                'id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Settings',
                'slug' => 'settings',
                'is_default' => true,
                'controller' => 'SettingController',
            ],
            [
                'id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Roles',
                'slug' => 'roles',
                'is_default' => true,
                'controller' => 'RoleController',
            ],
            [
                'id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Users',
                'slug' => 'users',
                'is_default' => false,
                'controller' => 'UserController',
            ],
            [
                'id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Menu',
                'slug' => 'menus',
                'is_default' => true,
                'controller' => 'MenuController',
            ],
        ];

        foreach ($data as $row) {
            $count = DB::table('mit_menus')->where('name', $row['name'])->count();
            if ($count && $count > 0) {
                continue;
            }
            DB::table('mit_menus')->insert($row);
        }
    }
}
