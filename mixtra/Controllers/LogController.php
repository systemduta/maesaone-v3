<?php

namespace Mixtra\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use DB;

class LogController extends MITController
{
    public function init()
    {
        $this->table = 'mit_logs';
        $this->button_bulk_action = false;
        $this->button_add = false;
        $this->button_edit = false;
        $this->button_delete = false;
        $this->show_numbering = true;

        $this->columns = [];
        $this->columns[] = ["label" => "Time Access", "field" => "created_at", "width" => 120, 'search_field' => 'a.created_at'];
        $this->columns[] = ["label" => "IP Address", "field" => "ip_address", "width" => 120, 'search_field' => 'a.ip_address'];
        $this->columns[] = ["label" => "User", "field" => "email", "width" => 200, 'search_field' => 'b.email'];
        $this->columns[] = ["label" => "Description", "field" => "description", 'search_field' => 'a.description'];

        $this->forms = [];
        $this->forms[] = ["label" => "Time Access", "name" => "created_at", "readonly" => true];
        $this->forms[] = ["label" => "IP Address", "name" => "ip_address", "readonly" => true];
        $this->forms[] = ["label" => "User Agent", "name" => "user_agent", "readonly" => true];
        $this->forms[] = ["label" => "URL", "name" => "url", "readonly" => true];
        $this->forms[] = [
            "label" => "User",
            "name" => "user_id",
            "type" => "select2",
            "datatable" => "users,name",
            "readonly" => true,
        ];
        $this->forms[] = ["label" => "Description", "name" => "description", "readonly" => true];
        $this->forms[] = ["label" => "Details", "name" => "details", "type" => "logs"];
    }

    public function collections()
    {
        return DB::table('mit_logs as a')
            ->join("users as b", "a.user_id", "b.id")
            ->select("a.*", "b.email")
            ->orderBy('a.created_at', 'desc');
    }

    public static function displayDiff($old_values, $new_values)
    {
        $diff = self::getDiff($old_values, $new_values);
        $table = '<table class="table table-striped"><thead><tr><th>Key</th><th>Old Value</th><th>New Value</th></thead><tbody>';
        foreach ($diff as $key => $value) {
            $old_value = isset($old_values[$key]) ? $old_values[$key] : null;
            $new_value = isset($new_values[$key]) ? $new_values[$key] : null;
            $table .= "<tr><td>$key</td><td>$old_value</td><td>$new_value</td></tr>";
        }
        $table .= '</tbody></table>';

        return $table;
    }

    private static function getDiff($old_values, $new_values)
    {
        unset($old_values['id']);
        unset($old_values['created_at']);
        unset($old_values['updated_at']);
        unset($new_values['created_at']);
        unset($new_values['updated_at']);

        return array_diff($old_values, $new_values);
    }
}
