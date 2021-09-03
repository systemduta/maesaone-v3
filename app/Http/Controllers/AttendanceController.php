<?php

namespace App\Http\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;

class AttendanceController extends MITController
{
    public function init()
    {
        $this->table = 'attendances';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];

        $this->columns[] = ["label" => "Employee", "field" => "employee_name", "search_field" => "b.name"];
        $this->columns[] = ["label" => "Trans Date", "field" => "trans_date"];
        $this->columns[] = ["label" => "Check Time", "field" => "check_time"];
        $this->columns[] = ["label" => "Type", "field" => "attendance_type"];
        $this->columns[] = ["label" => "Location", "field" => "location"];

        $this->forms = [];
        $this->forms[] = ["label" => "Trans Date", "name" => "trans_date", 'required' => true, 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "Check Time", "name" => "check_time", 'required' => true];
        $this->forms[] = ["label" => "Type", "name" => "attendance_type", 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "Location", "name" => "location", 'width'=>'col-sm-2'];
    }

    public function collections()
    {
        return DB::table('attendances as a')
            ->leftJoin('employees as b', 'a.employee_id', 'b.id')
            ->select("a.*", "b.name as employee_name")
            ->orderBy('a.id', 'desc');
    }
}
