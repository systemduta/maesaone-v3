<?php

namespace App\Http\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;

class LeaveController extends MITController
{
    public function init()
    {
        $this->table = 'leaves';
        $this->title_field = 'employee_id';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Company", "field" => "company_name"];
        $this->columns[] = ["label" => "Employee", "field" => "employee_name"];
        $this->columns[] = ["label" => "Type", "field" => "type"];
        $this->columns[] = ["label" => "From", "field" => "from"];
        $this->columns[] = ["label" => "To", "field" => "to"];
        $this->columns[] = ["label" => "Reason", "field" => "reason"];
        $this->columns[] = ["label" => "External ID", "field" => "external_id"];

        $this->forms = [];
        $this->forms[] = ["label" => "Company", "name" => "company_id", "type" => "select2", 'datatable' => 'companies,name'];
        $this->forms[] = ["label" => "Employee", "name" => "employee_id", "type" => "select2", 'datatable' => 'employees,name'];
        $this->forms[] = ["label" => "Type", "name" => "type", "type" => "select2", "dataenum" => ""];
        $this->forms[] = ["label" => "From", "name" => "from", "type" => "date", 'required' => true, 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "To", "name" => "to", "type" => "date", 'required' => true, 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "Reason", "name" => "reason", "type" => "textarea"];
        $this->forms[] = ["label" => "External #", "name" => "external_id", 'width'=>'col-sm-2'];
    }

    public function collections()
    {
        return DB::table($this->table)
            ->leftjoin('companies', $this->table.'.company_id', 'companies.id')
            ->leftjoin('employees', $this->table.'.employee_id', 'employees.id')
            ->select($this->table.".*", "companies.name AS company_name", "employees.name AS employee_name");
    }
}
