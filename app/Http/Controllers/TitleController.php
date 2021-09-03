<?php

namespace App\Http\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;
use MITBooster;

class TitleController extends MITController
{
    public function init()
    {
        $this->table = 'titles';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Code", "field" => "code"];
        $this->columns[] = ["label" => "Name", "field" => "name"];
        $this->columns[] = ["label" => "Company", "field" => "company_name"];
        $this->columns[] = ["label" => "Department", "field" => "department_name"];
        $this->columns[] = ["label" => "External ID", "field" => "external_id"];

        $this->forms = [];
        $this->forms[] = ["label" => "Code", "name" => "code", 'required' => true, 'width'=>'col-sm-2'];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];
        $this->forms[] = ["label" => "Company", "name" => "company_id", "type" => "select2", 'datatable' => 'companies,name', "value" => MITBooster::myCompanyID()];
        $this->forms[] = ["label" => "Department", "name" => "department_id", "type" => "select2", 'datatable' => 'departments,name'];
        $this->forms[] = ["label" => "External #", "name" => "external_id", 'width'=>'col-sm-2'];

        $tabpages = [];

        $pages = [];
        $columns = [];
        $columns[] = ["label" => "id", "name" => "id", "type" => "hidden", "value" => ""];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $pages[] = ['label'=>'CPF','name'=>'critical_performance_factor_page','type'=>'child','columns'=>$columns,'table'=>'critical_performance_factors','foreign_key'=>'title_id'];
        $tabpages[] = ['label'=>'Critical Performance Factor','name'=>'critical_performance_factor_tab','type'=>'tabpage','image'=>'fa fa-archive','pages'=>$pages];

        $pages = [];
        $columns = [];
        $columns[] = ["label" => "id", "name" => "id", "type" => "hidden", "value" => ""];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $pages[] = ['label'=>'Job Descrition','name'=>'job_descrition_page','type'=>'child','columns'=>$columns,'table'=>'job_descriptions','foreign_key'=>'title_id'];
        $tabpages[] = ['label'=>'Job Description','name'=>'job_descrition_tab','type'=>'tabpage','image'=>'fa fa-archive','pages'=>$pages];

        $pages = [];
        $columns = [];
        $columns[] = ["label" => "id", "name" => "id", "type" => "hidden", "value" => ""];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $pages[] = ['label'=>'Performance Umum','name'=>'performance_umum_page','type'=>'child','columns'=>$columns,'table'=>'performance_umums','foreign_key'=>'title_id'];
        $tabpages[] = ['label'=>'Performance Umum','name'=>'performance_umum_tab','type'=>'tabpage','image'=>'fa fa-archive','pages'=>$pages];
        $this->forms[] = ['name'=>'header','type'=>'tab','tabpages'=>$tabpages];
    }

    public function collections()
    {
        return DB::table($this->table)
            ->leftjoin('companies', $this->table.'.company_id', 'companies.id')
            ->leftjoin('departments', $this->table.'.department_id', 'departments.id')
            ->select($this->table.".*", "companies.name AS company_name", "departments.name AS department_name");
    }
}
