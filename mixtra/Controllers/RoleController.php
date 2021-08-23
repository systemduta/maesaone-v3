<?php

namespace Mixtra\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use DB;

class RoleController extends MITController
{
    public function init()
    {
        $this->table = 'mit_roles';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Name", "field" => "name"];
        $this->columns[] = [
            "label" => "Type",
            "field" => "is_superadmin",
            'callback_php' => '($row->is_superadmin)?"<span class=\"badge badge-pill badge-success mr-1\">Superadmin</span>":"<span class=\"badge badge-pill badge-warning mr-1\">Standard</span>"',
        ];

        $this->forms = [];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];
        $this->forms[] = ["label" => "Type", "name" => "is_superadmin", "type" => "radio", 'dataenum' => '1|Superadmin;0|Standard', 'required' => true, 'value' => 0];
        $this->forms[] = ["label" => "Menu", "name" => "menu", "type" => "menu", "exception" => true];
    }

    public function hook_after_add($id)
    {
        $this->setRoleDetail($id);
    }

    public function hook_after_edit($id)
    {
        $this->setRoleDetail($id);
    }

    public function setRoleDetail($id)
    {
        DB::table("mit_roles_menus")->where("mit_role_id", $id)->delete();

        $role = db::table('mit_roles')->find($id);
        if ($role != null && !$role->is_superadmin) {
            $privileges = Request::get('privileges');
            if($privileges != null) {
                foreach ($privileges as $menu_id => $data) {
                    $arrs = [
                        'mit_role_id' => $id,
                        'mit_menu_id' => $menu_id,
                        'is_visible' => isset($data['is_visible']) && $data['is_visible'] == "1" ? 1 : 0,
                        'is_create' => isset($data['is_create']) && $data['is_create'] == "1" ? 1 : 0,
                        'is_read' => isset($data['is_read']) && $data['is_read'] == "1" ? 1 : 0,
                        'is_edit' => isset($data['is_edit']) && $data['is_edit'] == "1" ? 1 : 0,
                        'is_delete' => isset($data['is_delete']) && $data['is_delete'] == "1" ? 1 : 0,
                    ];
                    DB::table("mit_roles_menus")->insert($arrs);
                }
            }
        }
    }
}
