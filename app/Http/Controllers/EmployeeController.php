<?php

namespace App\Http\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;

class EmployeeController extends MITController
{
    public function init()
    {
        $this->table = 'employees';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "NIK", "field" => "nik"];
        $this->columns[] = ["label" => "Name", "field" => "name"];
        $this->columns[] = ["label" => "Email", "field" => "email"];
        $this->columns[] = ["label" => "Phone", "field" => "mobile"];
        $this->columns[] = ["label" => "Branch", "field" => "branch"];

        $this->forms = [];
        $this->forms[] = ["label" => "Code", "name" => "code", 'required' => true, 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];
        $this->forms[] = ["label" => "External #", "name" => "external_id", 'width'=>'col-sm-2'];
    }
}
