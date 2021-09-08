<?php

namespace App\Http\Controllers;

use Mixtra\Controllers\MITController;
use Illuminate\Support\Facades\DB;
use Mixtra\Helpers\MITBooster;

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
        $company_id = MITBooster::myCompanyID();
        return DB::table('attendances as a')
            ->leftJoin('employees as b', 'a.employee_id', 'b.id')
            ->select("a.*", "b.name as employee_name", "b.company_id")
            ->when($company_id, function ($query, $company_id) {
                return $query->where("company_id", $company_id);
            })
            ->orderBy('a.id', 'desc');
    }
}
