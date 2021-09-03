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
        $this->columns[] = ["label" => "Branch", "field" => "branch_id"];

        $this->forms = [];
        $this->forms[] = ["label" => "NIK", "name" => "nik", 'required' => true, "width" => "col-sm-4", "end_group" => false];
        $this->forms[] = ["label" => "", "label_width" => "col-sm-0", "name" => "is_active", "type" => "checkbox", "dataenum" => "1|Active", "value" => true, "width" => "col-sm-2", "end_group" => false, "begin_group" => false];
        $this->forms[] = ["label" => "External #", "name" => "external_id", "width"=>'col-sm-2', "begin_group" => false];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];

        $this->forms[] = ["label" => "", "name" => "name", "type" => "hr", 'required' => true];

        $groups = [];
        $pane = [];
        $pane[] = ["label" => "Nickname", "label_width"=>'col-sm-3', "name" => "nickname", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Email", "label_width"=>'col-sm-3', "name" => "email", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Mobile", "label_width"=>'col-sm-3', "name" => "mobile", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Social Media", "label_width"=>'col-sm-3', "name" => "social_media", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Group Employee", "label_width"=>'col-sm-3', "name" => "group_employee", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Employee Type", "label_width"=>'col-sm-3', "name" => "employee_type", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Class", "label_width"=>'col-sm-3', "name" => "class", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Level", "label_width"=>'col-sm-3', "name" => "level", "width"=>'col-sm-9'];
        $groups[] = ['pane'=>$pane, 'name'=>'header_left', "type" => "pane", "width"=>'col-sm-6'];

        $pane = [];
        $pane[] = ["label" => "Branch", "label_width"=>'col-sm-3', "name" => "branch_id", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Department", "label_width"=>'col-sm-3', "name" => "department_id", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Leader", "label_width"=>'col-sm-3', "name" => "mobile", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Title", "label_width"=>'col-sm-3', "name" => "social_media", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Unit", "label_width"=>'col-sm-3', "name" => "social_media", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Segment", "label_width"=>'col-sm-3', "name" => "social_media", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Max Leave", "label_width"=>'col-sm-3', "name" => "social_media", "width"=>'col-sm-9'];
        $pane[] = ["label" => "Leave", "label_width"=>'col-sm-3', "name" => "social_media", "width"=>'col-sm-9'];
        $groups[] = ['pane'=>$pane, 'name'=>'header_right', "type" => "pane", "width"=>'col-sm-6'];
        
        $this->forms[] = array('name'=>'header','type'=>'group','groups'=>$groups);

        $tabpages = [];
        $pages = [];
        $pages[] = ["label" => "Gender", "name" => "gender", "type" => "select2", "dataenum" => "Male;Female"];
        $pages[] = ["label" => "Province of Birth", "name" => "province_of_birth"];
        $pages[] = ["label" => "City of Birth", "name" => "city_of_birth"];
        $pages[] = ["label" => "Date of Birth", "name" => "date_of_birth", "type" => "date"];
        $pages[] = ["label" => "Blood Type", "name" => "blood_type", "type" => "select2", "dataenum" => "A+;A-;B+;B-;AB+;AB-;O+;O-"];
        $pages[] = ["label" => "Religion", "name" => "religion", "type" => "select2", "dataenum" => "Budha;Hindu;Islam;Katolik;Kristen;Lainnya"];
        $pages[] = ["label" => "Working Day", "name" => "working_day_id"];
        $pages[] = ["label" => "Shift", "name" => "shift_id", "width"=>'col-sm-6', "end_group" => false];
        $pages[] = ["label" => "", "label_width" => "col-sm-0", "name" => "is_shift", "type" => "checkbox", "dataenum" => "1|Shift", "width"=>'col-sm-4', "begin_group" => false];
        $pages[] = ["label" => "Start Working", "name" => "start_working", "type" => "date"];
        $pages[] = ["label" => "End Working", "name" => "end_working", "type" => "date"];
        $tabpages[] = ['label'=>'Detail','name'=>'detail','type'=>'tabpage','image'=>'fa fa-file-invoice','pages'=>$pages];

        $pages = [];
        $pages[] = ["label" => "Type 1", "name" => "type_1"];
        $tabpages[] = ['label'=>'Identity','name'=>'identity','type'=>'tabpage','image'=>'fa fa-file-invoice','pages'=>$pages];

        $this->forms[] = array('name'=>'detail','type'=>'tab','tabpages'=>$tabpages);


    }
}
