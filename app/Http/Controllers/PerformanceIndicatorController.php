<?php

namespace App\Http\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;
use MITBooster;

class PerformanceIndicatorController extends MITController
{
    public function init()
    {
        $this->table = 'departments';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Code", "field" => "code"];
        $this->columns[] = ["label" => "Name", "field" => "name"];
        $this->columns[] = ["label" => "Company", "field" => "company_name", "join"=>"companies"];
        $this->columns[] = ["label" => "External ID", "field" => "external_id"];

        $this->forms = [];
        $this->forms[] = ["label" => "Code", "name" => "code", 'required' => true, 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];
        $this->forms[] = ["label" => "Company", "name" => "company_id", "type" => "select2", 'datatable' => 'companies,name', "value" => MITBooster::myCompanyID()];
        $this->forms[] = ["label" => "External #", "name" => "external_id", 'width'=>'col-sm-2'];
    }

    public function collections()
    {
        return DB::table($this->table)
            ->leftjoin('companies', $this->table.'.company_id', 'companies.id')
            ->select($this->table.".*", "companies.name AS company_name");
    }
}
