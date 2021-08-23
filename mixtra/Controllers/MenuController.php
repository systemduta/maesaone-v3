<?php

namespace Mixtra\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use DB;

class MenuController extends MITController
{
    public function init()
    {
        $this->table = 'mit_menus';
        $this->title_field = 'name';
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Name", "field" => "name"];
        $this->columns[] = ["label" => "Slug", "field" => "slug"];
        $this->columns[] = ["label" => "Controller", "field" => "controller"];
        $this->columns[] = ["label" => "Default", "field" => "is_default",
            'callback_php' => '($row->is_default)?"<span class=\"badge badge-pill badge-success py-1 px-3\">Yes</span>":"<span class=\"badge badge-pill badge-warning py-1 px-3\">No</span>"'];

        $this->forms = [];
        $this->forms[] = ["label" => "Name", "name" => "name", 'required' => true, 'width' => 'col-sm-4'];
        $this->forms[] = ["label" => "Slug", "name" => "slug"];
        $this->forms[] = ["label" => "Controller", "name" => "controller"];
        $this->forms[] = ["label" => "Default", "name" => "is_default", "type" => "radio", 'dataenum' => '1|Yes;0|No', 'required' => true, 'value' => 0];
    }
}
