<?php

namespace App\Http\Controllers;

use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;

class UserController extends MITController
{
    private $module_name = true;

    public function init()
    {
        $this->table = 'users';
        $this->title_field = 'email';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Name", "field" => "name", "search_field" => "a.name"];
        $this->columns[] = ["label" => "Email", "field" => "email", "width" => 120];
        $this->columns[] = ["label" => "Verified", "field" => "email_verified_at", "width" => 120, "callback_php" => "date('d M Y H:i',strtotime([email_verified_at]))"];
        $this->columns[] = ["label" => "Role", "field" => "role_name", "width" => 150, "search_field" => "b.name"];
        $this->columns[] = ["label" => "Company", "field" => "company_name", "join"=>"companies"];

        $this->forms = [];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];
        $this->forms[] = ["label" => "Email", "name" => "email"];
        $this->forms[] = ["label" => "Company", "name" => "company_id", "type" => "select2", 'datatable' => 'companies,name'];
        // $this->forms[] = ["label" => "Photo", "name" => "photo", "type"=>"upload", "class" => "image", "help"=>"Recommended resolution is 90x90px"];
        if($this->module_name != 'Profile') {
            $this->forms[] = ["label" => "Role", "name" => "mit_role_id", "type" => "select2", 'datatable' => 'mit_roles,name', 'required' => true, 'value' => 1];
        }
        $this->forms[] = ["label" => "Password", "name" => "password", "type" => "password", 'help' => 'Please leave empty if not change'];
        $this->forms[] = ["label" => "Password Confirmation", "name" => "password_confirmation", "type" => "password", 'help' => 'Please leave empty if not change'];
    }

    public function collections()
    {
        return DB::table('users as a')
            ->leftJoin('mit_roles as b', 'a.mit_role_id', 'b.id')
            ->leftjoin('companies', 'a.company_id', 'companies.id')
            ->select("a.*", "b.name as role_name", "companies.name AS company_name");
    }

    public function hook_before_add(&$arr)
    {
        unset($arr['password_confirmation']);
    }

    public function hook_before_edit(&$arr,$id) { 
        unset($arr['password_confirmation']);
	}

    public function getProfile() {
        $this->module_name = 'Profile';
        return $this->getEdit(MITBooster::myId());
    }
}
