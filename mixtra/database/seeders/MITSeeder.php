<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\MenuSeeder;

class MITSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Please wait updating the data...');

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            MenuSeeder::class,
        ]);

        $this->command->info('Updating the data completed !');
    }
}
