<?php

namespace App\Http\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use Mixtra\Controllers\MITController;
use DB;

class PerformanceUmumController extends MITController
{
    public function init()
    {
        $this->table = 'job_descriptions';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Name", "field" => "name"];
        $this->columns[] = ["label" => "Company", "field" => "company_name", "join"=>"companies"];

        $this->forms = [];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true];
        // $this->forms[] = ["label" => "Type", "name" => "type", "type" => "select2", 'dataenum' => 'Job Description;Performance Umum', "value" => "Performance Umum"];
        $this->forms[] = ["label" => "Type", "name" => "type", "type" => "hidden", "value" => "Performance Umum"];
        $this->forms[] = ["label" => "Company", "name" => "company_id", "type" => "select2", 'datatable' => 'companies,name'];
        $this->forms[] = ["label" => "Critical Performance Factor (CPF)", "name" => "critical_performance_factor_id", "type" => "select2", 'datatable' => 'critical_performance_factors,name'];

        $this->forms[] = ["label" => "", "name" => "hr", "type" => "hr"];

        $columns = [];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $this->forms[] = ['label'=>'4 Point','name'=>'measurement4','type'=>'measurement','columns'=>$columns,'table'=>'measurements_4','foreign_key'=>'job_description_id'];

        $this->forms[] = ["label" => "", "name" => "hr", "type" => "hr"];

        $columns = [];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $this->forms[] = ['label'=>'3 Point','name'=>'measurement3','type'=>'measurement','columns'=>$columns,'table'=>'measurements_3','foreign_key'=>'job_description_id'];

        $this->forms[] = ["label" => "", "name" => "hr", "type" => "hr"];

        $columns = [];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $this->forms[] = ['label'=>'2 Point','name'=>'measurement2','type'=>'measurement','columns'=>$columns,'table'=>'measurements_2','foreign_key'=>'job_description_id'];

        $this->forms[] = ["label" => "", "name" => "hr", "type" => "hr"];

        $columns = [];
        $columns[] = ["label" => "Name", "name" => "name", "type" => "text"];
        $this->forms[] = ['label'=>'1 Point','name'=>'measurement1','type'=>'measurement','columns'=>$columns,'table'=>'measurements_1','foreign_key'=>'job_description_id'];
    }

    public function collections()
    {
        return DB::table($this->table)
            ->leftjoin('companies', $this->table.'.company_id', 'companies.id')
            ->select($this->table.".*", "companies.name AS company_name")
            ->where($this->table.".type", "Performance Umum");
    }
}
