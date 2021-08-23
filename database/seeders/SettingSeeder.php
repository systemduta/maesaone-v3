<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            //APPLICATION SETTING
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'app_name',
                'label' => 'Application Name',
                'group_setting' => 'Application',
                'content' => 'Mixtra',
                'content_input_type' => 'text',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'logo',
                'label' => 'Logo',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'Application',
                'dataenum' => null,
                'helper' => 'PNG File (Recomended Size: 100 x 100px, 72dpi)',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'logo_white',
                'label' => 'Logo White',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'Application',
                'dataenum' => null,
                'helper' => 'PNG File (Recomended Size: 100 x 100px, 72dpi)',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'logo_text',
                'label' => 'Logo Text',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'Application',
                'dataenum' => null,
                'helper' => 'PNG File (Recomended Size: 100 x 100px, 72dpi)',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'logo_white_text',
                'label' => 'Logo White Text',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'Application',
                'dataenum' => null,
                'helper' => 'PNG File (Recomended Size: 100 x 100px, 72dpi)',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'favicon',
                'label' => 'Favicon',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'Application',
                'dataenum' => null,
                'helper' => 'PNG File (Recomended Size: 32 x 32px, 72dpi)',
            ],
        ];

        foreach ($data as $row) {
            $count = DB::table('mit_settings')->where('name', $row['name'])->count();
            if ($count) {
                if ($count > 1) {
                    $newsId = DB::table('mit_settings')->where('name', $row['name'])->orderby('id', 'asc')->take(1)->first();
                    DB::table('mit_settings')->where('name', $row['name'])->where('id', '!=', $newsId->id)->delete();
                }
                continue;
            }
            DB::table('mit_settings')->insert($row);
        }
    }
}
