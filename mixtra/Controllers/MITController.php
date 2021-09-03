<?php
namespace Mixtra\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;

use Mixtra\Controllers\LogController;
use Mixtra\Helpers\MITExport;

use MITBooster;
use Request;
use DB;
use Schema;
use Validator;
use Hash;
use Session;

class MITController extends Controller
{
    // Bulk Property
    public $button_bulk_action = true;
    public $button_selected = [];
    public $pre_card_header_html = null;

    // Filter Propery
    public $button_filter = true;
    public $search_text = true;
    public $is_report = false;

    // Grid Action Property
    public $button_table_action = true;
    public $button_action_width = null;
    public $addaction = [];

    // Button Property
    public $button_reload = true;
    public $button_add = true;
    public $button_edit = true;
    public $button_detail = true;
    public $button_delete = true;
    public $button_export = true;

    // Grid Property
    public $columns = [];
    public $limit = 20;
    public $primary = 'id';
    public $link_first_column = true;
    public $show_numbering = false;

    // Form Property
    public $forms = [];
    public $table = '';
    public $title_field = 'name';
    public $is_export = false;

    // Filter Property
    public $filters = [];


    public function init()
    {
    }

    public function hook_item_data(&$row)
    {
    }

    public function hook_row_data($row, $index, &$value)
    {
    }

    public function hook_before_add(&$arr)
    {
    }

    public function hook_after_add($id)
    {
    }

    public function hook_before_edit(&$arr, $id)
    {
    }

    public function hook_after_edit($id)
    {
    }

    public function hook_before_delete($id)
    {
    }

    public function hook_after_delete($id)
    {
    }

    public function collections()
    {
        return DB::table($this->table);
    }

    public function mitLoader()
    {
        $this->init();
        $this->data['button_bulk_action'] = $this->button_bulk_action;
        $this->data['button_table_action'] = $this->button_table_action;
        $this->data['button_action_width'] = $this->button_action_width;

        $this->data['button_filter'] = $this->button_filter;
        $this->data['search_text'] = $this->search_text;
        $this->data['is_report'] = $this->is_report;

        $this->data['button_reload'] = $this->button_reload;
        $this->data['button_add'] = $this->button_add;
        $this->data['button_edit'] = $this->button_edit;
        $this->data['button_detail'] = $this->button_detail;
        $this->data['button_delete'] = $this->button_delete;
        $this->data['button_export'] = $this->button_export;

        $this->data['button_selected'] = $this->button_selected;

        $this->data['pre_card_header_html'] = $this->pre_card_header_html;

        $this->data['columns'] = $this->columns;
        $this->data['show_numbering'] = $this->show_numbering;

        $this->data['forms'] = $this->forms;
        $this->data['filters'] = $this->filters;

        view()->share($this->data);
    }

    public function getIndex()
    {
        $this->mitLoader();
        $this->data['module'] = MITBooster::getCurrentModule();

        if (!MITBooster::isView()) {
            MITBooster::insertLog(trans('locale.log_try_view', ['module' => $this->data['module']->name]));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        $limit = (Request::get('limit')) ? Request::get('limit') : $this->limit;
        $query = $this->collections();

        // Searching
        if (Request::get('q')) {
            $columns_table = $this->columns;
            $query->where(function ($w) use ($columns_table) {
                $condition = Request::get("q");
                $condition = str_replace(" ", "%", $condition);
                foreach ($columns_table as $col) {
                    if (!isset($col['field'])) {
                        continue;
                    }
                    $search_field = isset($col['search_field']) ? $col['search_field'] : $col['field'];
                    if ($search_field != 'skip') {
                        $w->orWhere($search_field, "like", "%".$condition."%");
                    }
                }
            });
        }

        $params = Request::all();
        // Filter
        $columns_table = $this->columns;
        $filters = $this->filters;
        $filter_exists = false;
        $filter_exists = isset($params['submit']) && $params['submit'] == 'Search';
        $query->where(function ($w) use ($params, $filters, $columns_table, &$filter_exists) {
            foreach ($params as $key => $param) {
                if (substr($key, 0, 7) == "filter_") {
                    $filter_exists = true;
                    if ($param != '') {
                        $k = str_replace("filter_", "", $key);

                        foreach ($this->columns as $column) {
                            $field = $column['field'];
                            if ($field == $k) {
                                $field_search = isset($column['search_field']) ? $column['search_field'] : $column['field'];
                            }
                        }

                        if (!isset($field_search)) {
                            continue;
                        }

                        foreach ($this->filters as $filter) {
                            if ($field_search != 'skip') {
                                if ($filter['name'] == $k) {
                                    $value = $param;
                                    if ($filter['type'] == "periods") {
                                        $values = explode(' to ', $value);
                                        if (count($values)==1) {
                                            $values[1] = $values[0].' 23:59:59';
                                            $values[0] = $values[0].' 00:00:00';
                                        } else {
                                            $values[0] = $values[0].' 00:00:00';
                                            $values[1] = $values[1].' 23:59:59';
                                        }
                                        $w->whereBetween($field_search, $values);
                                    } elseif ($filter['type'] == "number") {
                                        $w->Where($field_search, "=", $value);
                                    } else {
                                        $value = str_replace(" ", "%", $value);
                                        $w->WhereRaw("$field_search like '%$value%'");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });

        // Sorting
        foreach ($params as $key => $param) {
            if (substr($key, 0, 5) == "sort_") {
                $k = str_replace("sort_", "", $key);
                foreach ($this->columns as $column) {
                    $field = $column['field'];
                    $field_search = isset($column['search_field']) ? $column['search_field'] : $column['field'];
                    if ($field == $k) {
                        $query->reorder($field, $param);
                    }
                }
            }
        }
        if ($this->is_export) {
            $data = $query->get();
            if ($data != null) {
                foreach ($data as $row) {
                    $this->hook_item_data($row);
                }
            }
            return $data;
        }
        if ($this->is_report && !$filter_exists) {
            $query = db::table("users")->where('id', 0);
        }
        // dd($query->toSql());
        $result = $query->paginate($limit);

        $html_contents = [];
        $page = (Request::get('page')) ? Request::get('page') : 1;
        $number = ($page - 1) * $limit + 1;
        if ($result != null) {
            foreach ($result as $row) {
                $this->hook_item_data($row);

                $html_content = [];

                if ($this->button_bulk_action) {
                    $html_content[] = "<input type='checkbox' class='checkbox' name='checkbox[]' value='".$row->{$this->primary}."'/>";
                }

                if ($this->show_numbering) {
                    $html_content[] = '<div class="text-right">'.$number.'.</div>';
                    $number++;
                }

                $first_column = true;
                foreach ($this->columns as $col) {
                    if (isset($col['hide']) && $col['hide']) {
                        continue;
                    }
                    $value = @$row->{$col['field']};
                    $label = $col['label'];

                    if (isset($col['callback_php'])) {
                        foreach ($row as $k => $v) {
                            $col['callback_php'] = str_replace("[".$k."]", "'".$v."'", $col['callback_php']);
                        }
                        @eval("\$value = ".$col['callback_php'].";");
                    }

                    if ($this->link_first_column && $first_column) {
                        if (MITBooster::isUpdate() && $this->button_edit) {
                            $value = "<a href='".MITBooster::mainpath('edit/').$row->{$this->primary}."?return_url=".urlencode(Request::fullUrl())."'>".$value."</a>";
                        } elseif (MITBooster::isRead() && $this->button_detail) {
                            $value = "<a href='".MITBooster::mainpath('detail/').$row->{$this->primary}."?return_url=".urlencode(Request::fullUrl())."'>".$value."</a>";
                        }
                    }
                    $first_column = false;
                    $html_content[] = $value;
                }

                if ($this->button_table_action) {
                    $addaction = $this->addaction;
                    $button_edit = $this->button_edit;
                    $button_detail = $this->button_detail;
                    $button_delete = $this->button_delete;
                    $primary = $this->primary;
                    $width = "min-width:100px;";
                    if ($this->button_action_width) {
                        $width = "min-width:".$this->button_action_width."px;";
                    }
                    if(MITBooster::isUpdate() || MITBooster::isDelete() || MITBooster::isRead()) {
                        $html_content[] = "<div class='btn-group text-center' style='display:block;'>".view('mixtra::default.action', compact('addaction', 'row', 'button_edit', 'button_detail', 'button_delete', 'primary'))->render()."</div>";
                    }
                }

                foreach ($html_content as $index => $value) {
                    $this->hook_row_data($row, $index, $value);
                    $html_content[$index] = $value;
                }

                $html_contents[] = $html_content;
            }
        }

        $this->data['html_contents'] = $html_contents;
        $this->data['result'] = $result;
        $this->data['limit'] = $limit;

        return view("mixtra::default.index", $this->data);
    }

    public function getAdd()
    {
        $this->mitLoader();
        $this->data['module'] = MITBooster::getCurrentModule();
        $this->data['command'] = 'add';

        if (!MITBooster::isCreate() || $this->button_add == false) {
            MITBooster::insertLog(trans('mixtra.log_try_add', ['module' => MITBooster::getCurrentModule()->name]));
            return MITBooster::redirect(MITBooster::adminPath(), trans("locale.denied_access"));
        }

        return view('mixtra::default.detail', $this->data);
    }

    public function getEdit($id)
    {
        $this->mitLoader();
        $this->data['module'] = MITBooster::getCurrentModule();
        $this->data['command'] = 'edit';
        $this->data['row'] = DB::table($this->table)->where($this->primary, $id)->first();

        if (!MITBooster::isRead() || $this->button_edit == false) {
            MITBooster::insertLog(trans("locale.log_try_edit", [
                'name' => $row->{$this->title_field},
                'module' => MITBooster::getCurrentModule()->name,
            ]));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        return view('mixtra::default.detail', $this->data);
    }

    public function getDetail($id)
    {
        $this->mitLoader();
        $this->data['module'] = MITBooster::getCurrentModule();
        $this->data['command'] = 'detail';
        $this->data['row'] = DB::table($this->table)->where($this->primary, $id)->first();

        if (!MITBooster::isRead() || $this->button_detail == false) {
            MITBooster::insertLog(trans("locale.log_try_view", [
                'name' => $row->{$this->title_field},
                'module' => MITBooster::getCurrentModule()->name,
            ]));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        return view('mixtra::default.detail', $this->data);
    }

    public function postAddSave()
    {
        $this->mitLoader();
        if (!MITBooster::isCreate()) {
            MITBooster::insertLog(trans('locale.log_try_add_save', [
                'name' => Request::input($this->title_field),
                'module' => MITBooster::getCurrentModule()->name,
            ]));
            return MITBooster::redirect(MITBooster::adminPath(), trans("locale.denied_access"));
        }

        $this->validation();
        $this->input_assignment();

        if (Schema::hasColumn($this->table, 'created_at')) {
            $this->arr['created_at'] = date('Y-m-d H:i:s');
        }

        $this->hook_before_add($this->arr);
        $id = DB::table($this->table)->insertGetId($this->arr);
        $this->hook_after_add($id);

        $this->input_additional($id);

        $this->return_url = isset($this->return_url) ? $this->return_url : Request::get('return_url');

        MITBooster::insertLog(trans("locale.log_add", [
            'name' => $this->arr[$this->title_field],
            'module' => MITBooster::getCurrentModule()->name
        ]));

        if ($this->return_url) {
            if (Request::get('submit') == trans('locale.button_save_more')) {
                return MITBooster::redirect(Request::server('HTTP_REFERER'), trans("locale.alert_add_data_success"), 'success');
            } else {
                return MITBooster::redirect($this->return_url, trans("locale.alert_add_data_success"), 'success');
            }
        } else {
            if (Request::get('submit') == trans('locale.button_save_more')) {
                return MITBooster::redirect(MITBooster::mainpath('add'), trans("locale.alert_add_data_success"), 'success');
            } else {
                return MITBooster::redirect(MITBooster::mainpath(), trans("locale.alert_add_data_success"), 'success');
            }
        }
    }

    public function postEditSave($id)
    {
        $this->mitLoader();
        $row = DB::table($this->table)->where($this->primary, $id)->first();
        if (!MITBooster::isUpdate()) {
            MITBooster::insertLog(trans("locale.log_try_add", [
                'name' => $row->{$this->title_field},
                'module' => MITBooster::getCurrentModule()->name]));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        $this->validation();
        $this->input_assignment();

        if (Schema::hasColumn($this->table, 'updated_at')) {
            $this->arr['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->hook_before_edit($this->arr, $id);
        DB::table($this->table)->where($this->primary, $id)->update($this->arr);
        $this->hook_after_edit($id);

        $this->input_additional($id);

        $this->return_url = isset($this->return_url) ? $this->return_url : Request::get('return_url');

        $old_values = json_decode(json_encode($row), true);
        MITBooster::insertLog(trans("locale.log_update", [
            'name' => $this->arr[$this->title_field],
            'module' => MITBooster::getCurrentModule()->name,
        ]), LogController::displayDiff($old_values, $this->arr));

        if ($this->return_url) {
            if (Request::get('submit') == trans('locale.button_save_close')) {
                return MITBooster::redirect(MITBooster::mainpath(), trans("locale.alert_update_data_success"), 'success');
            } else {
                return MITBooster::redirect($this->return_url, trans("locale.alert_update_data_success"), 'success');
            }
        } else {
            if (Request::get('submit') == trans('locale.button_save_close')) {
                return MITBooster::redirect(MITBooster::mainpath('add'), trans("locale.alert_update_data_success"), 'success');
            } else {
                return MITBooster::redirect(MITBooster::mainpath(), trans("locale.alert_update_data_success"), 'success');
            }
        }
    }

    public function getDelete($id)
    {
        $this->mitLoader();
        $row = DB::table($this->table)->where($this->primary, $id)->first();

        if (!MITBooster::isDelete() || $this->button_delete == false) {
            MITBooster::insertLog(trans("locale.log_try_delete", [
                'name' => $row->{$this->title_field},
                'module' => MITBooster::getCurrentModule()->name,
            ]));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        //insert log
        MITBooster::insertLog(trans("locale.log_delete", [
            'name' => $row->{$this->title_field},
            'module' => MITBooster::getCurrentModule()->name
        ]));

        $this->hook_before_delete($id);

        if (Schema::hasColumn($this->table, 'deleted_at')) {
            DB::table($this->table)->where($this->primary, $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table($this->table)->where($this->primary, $id)->delete();
        }

        $this->hook_after_delete($id);

        $url = Request::get('return_url') ?: MITBooster::referer();

        return MITBooster::redirect($url, trans("locale.alert_delete_data_success"), 'success');
    }

    public function getExportData()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);

        $this->is_export = true;
        $result = $this->getIndex();
        $filetype = Request::input('format');
        $filename = MITBooster::getCurrentModule()->name;
        $filename = $filename.'_'.now()->timestamp;

        if (count($result) == 0) {
            return redirect(MITBooster::urlFullText('format'))->with(['message_type' => 'warning', 'message' => trans("locale.export_no_data")]);
        }
        switch ($filetype) {
            case "pdf":
                $view = view('mixtra::export', compact('result'))->render();
                // return $view;

                $dompdf = new Dompdf();
                $dompdf->loadHtml($view);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                return $dompdf->stream($filename.'.pdf');
            case 'xlsx':
                return $this->downloadData($result, $filename.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            case 'xls':
                return $this->downloadData($result, $filename.'.xls', \Maatwebsite\Excel\Excel::XLS);
            case 'csv':
                return $this->downloadData($result, $filename.'.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }

    public function downloadData($data, $filename, $writerType)
    {
        $new_data = $data->map(function ($dt){
            $temp = collect([]);
            foreach ($this->columns as $column) {
                $temp->put($column['field'], $dt->{$column['field']});
            }
            return $temp;
        });
        return Excel::download(new \App\Exports\MITExport($new_data, $this->columns), $filename, $writerType);
    }

    public function validation($id = null)
    {
        $request_all = Request::all();
        $array_input = [];
        foreach ($this->forms as $di) {
            if (!$this->validation_input($di, $request_all, $array_input)) {
                continue;
            }
        }
        $validator = Validator::make($request_all, $array_input);

        if ($validator->fails()) {
            $message = $validator->messages();
            $message_all = $message->all();

            if (Request::ajax()) {
                $res = response()->json([
                    'message' => trans('mixtra.alert_validation_error', ['error' => implode(', ', $message_all)]),
                    'message_type' => 'warning',
                ])->send();
                exit;
            } else {
                $res = redirect()->back()->with("errors", $message)->with([
                    'message' => trans('locale.alert_validation_error', ['error' => implode(', ', $message_all)]),
                    'message_type' => 'warning',
                ])->withInput();
                \Session::driver()->save();
                $res->send();
                exit;
            }
        }
    }

    public function validation_input($di, $request_all, &$array_input)
    {
        $ai = [];
        $name = $di['name'];
        if (! $name) {
            return false;
        }

        if (!isset($di['type'])) {
            $di['type'] = 'text';
        }

        if (@$di['min']) {
            $ai[] = 'min:'.$di['min'];
        }
        if (@$di['max']) {
            $ai[] = 'max:'.$di['max'];
        }
        if (@$di['image']) {
            $ai[] = 'image';
        }
        if (@$di['mimes']) {
            $ai[] = 'mimes:'.$di['mimes'];
        }

        if ($di['type'] == 'money') {
            $request_all[$name] = preg_replace('/[^\d-]+/', '', $request_all[$name]);
        }

        if ($di['type'] == 'tab') {
            foreach ($di['tabpages'] as $tabpage) {
                foreach ($tabpage['pages'] as $page) {
                    if (!$this->validation_input($page, $request_all, $array_input)) {
                        continue;
                    }
                }
            }
            return false;
        }

        if (@$di['validation']) {
            $exp = explode('|', $di['validation']);
            if ($exp && count($exp)) {
                foreach ($exp as &$validationItem) {
                    if (substr($validationItem, 0, 6) == 'unique') {
                        $parseUnique = explode(',', str_replace('unique:', '', $validationItem));
                        $uniqueTable = ($parseUnique[0]) ?: $this->table;
                        $uniqueColumn = ($parseUnique[1]) ?: $name;
                        $first_data = db::table($uniqueTable)->where($uniqueColumn, $request_all[$uniqueColumn])->first();
                        $uniqueIgnoreId = Session::get('current_row_id', 0);

                        //Make sure table name
                        $uniqueTable = MIT::parseSqlTable($uniqueTable)['table'];

                        //Rebuild unique rule
                        $uniqueRebuild = [];
                        $uniqueRebuild[] = $uniqueTable;
                        $uniqueRebuild[] = $uniqueColumn;
                        if ($uniqueIgnoreId) {
                            $uniqueRebuild[] = $uniqueIgnoreId;
                        } else {
                            $uniqueRebuild[] = 'NULL';
                        }

                        //Check whether deleted_at exists or not
                        if (MIT::isColumnExists($uniqueTable, 'deleted_at')) {
                            $uniqueRebuild[] = MIT::findPrimaryKey($uniqueTable);
                            $uniqueRebuild[] = 'deleted_at';
                            $uniqueRebuild[] = 'NULL';
                        }
                        $uniqueRebuild = array_filter($uniqueRebuild);
                        $validationItem = 'unique:'.implode(',', $uniqueRebuild);
                    }
                }
            } else {
                $exp = [];
            }

            $validation = implode('|', $exp);
            $array_input[$name] = $validation;
        } else {
            $array_input[$name] = implode('|', $ai);
        }
        return true;
    }

    public function input_assignment($id = null)
    {
        $hide_form = (Request::get('hide_form')) ? unserialize(Request::get('hide_form')) : [];
        foreach ($this->forms as $ro) {
            if (!$this->input_arr($ro)) {
                continue;
            }
        }
    }

    public function input_arr($ro)
    {
        $name = $ro['name'];
        if (!isset($ro['type'])) {
            $ro['type'] = 'text';
        }

        if ($ro['type'] == 'tabpage' || $ro['type'] == 'hr' || $ro['type'] == 'custom'
            || $ro['type'] == 'blank' || $ro['type'] == 'image' || $ro['type'] == 'label') {
            return false;
        }

        if (! $name) {
            return false;
        }

        if (isset($ro['exception']) && $ro['exception']) {
            return false;
        }

        if ($ro['type'] == 'child' || $ro['type'] == 'measurement') {
            return false;
        }

        if ($name == 'hide_form') {
            return false;
        }

        $inputdata = Request::get($name);

        if ($ro['type'] == 'number') {
            $inputdata = str_replace(',', '', $inputdata);
        }

        if ($ro['type'] == 'datetime') {
            dd(strtotime($inputdata));
            if ($inputdata) {
                // if ($ro['datetype'] == 'date') {
                //     $inputdata = date('Y-m-d', strtotime($inputdata));
                // } elseif ($ro['datetype'] == 'date') {
                //     $inputdata = date('H:i:s', strtotime($inputdata));
                // } else {
                    $inputdata = date('Y-m-d H:i:s', strtotime($inputdata));
                // }
                    dd($inputdata);
            }
        }

        if ($name) {
            if ($inputdata != '') {
                $this->arr[$name] = $inputdata;
            } else {
                $schema = db::select(db::raw("select IS_NULLABLE from information_schema.columns where table_name = '$this->table' and column_name = '$name'"));
                $nullable = false;
                if (count($schema) > 0) {
                    $nullable = $schema[0]->IS_NULLABLE == "YES";
                }

                if ($nullable && $ro['type'] != 'upload') {
                    return false;
                }

                $this->arr[$name] = '';
            }
        }

        $password_candidate = explode(',', config('mixtra.password_fields_candidate'));
        if (in_array($name, $password_candidate)) {
            if (! empty($this->arr[$name])) {
                $this->arr[$name] = Hash::make($this->arr[$name]);
            } else {
                unset($this->arr[$name]);
            }
        }

        // if ($ro['type'] == 'checkbox') {
        //     if (is_array($inputdata)) {
        //         if ($ro['datatable'] != '') {
        //             $table_checkbox = explode(',', $ro['datatable'])[0];
        //             $field_checkbox = explode(',', $ro['datatable'])[1];
        //             $table_checkbox_pk = MIT::pk($table_checkbox);
        //             $data_checkbox = DB::table($table_checkbox)->whereIn($table_checkbox_pk, $inputdata)->pluck($field_checkbox)->toArray();
        //             $this->arr[$name] = implode(";", $data_checkbox);
        //         } else {
        //             $this->arr[$name] = implode(";", $inputdata);
        //         }
        //     }
        // }


        if ($ro['type'] == 'select' || $ro['type'] == 'select2') {
            if (isset($ro['datatable'])) {
                if ($inputdata == '') {
                    $this->arr[$name] = 0;
                }
            }
        }

        if (@$ro['type'] == 'upload') {
            $this->arr[$name] = MITBooster::uploadFile($name);
            if ($this->arr[$name] == null) {
                $this->arr[$name] = Request::get('_'.$name);
            }
        }

        if (@$ro['type'] == 'multiupload') {
            return false;
        }


        // if (@$ro['type'] == 'wysiwyg') {
        //     dd(Request::all());
        // }

        // if (@$ro['type'] == 'upload_video') {
        //     $this->arr[$name] = MITBooster::uploadFile($name, $ro['encrypt'] || $ro['upload_encrypt'], $ro['resize_width'], $ro['resize_height'], $ro['target_folder'], MIT::myId());

        //     if (! $this->arr[$name]) {
        //         $this->arr[$name] = Request::get('_'.$name);
        //     }
        // }

        // if (@$ro['type'] == 'filemanager') {
        //     $filename = str_replace('/'.config('lfm.prefix').'/'.config('lfm.files_folder_name').'/', '', $this->arr[$name]);
        //     $url = 'uploads/'.$filename;
        //     $this->arr[$name] = $url;
        // }
        return true;
    }

    public function input_additional($id)
    {
        $repeatFlag = false;
        foreach ($this->forms as $ro) {
            $name = $ro['name'];
            if (! $name) {
                continue;
            }

            if (isset($ro['type']) && $ro['type'] == 'multiupload') {
                $media = Request::get($name);
                db::table($name)->where($ro['primary'], $id)->delete();
                if (isset($media) && $media != null) {
                    foreach ($media as $item) {
                        db::table($name)->insert([
                        'created_at' => date('Y-m-d H:i:s'),
                        $ro['primary'] => $id,
                        'mit_media_id' => $item,
                    ]);
                    }
                }
            }
            
            if (isset($ro['type']) && ($ro['type'] == 'measurement')) {
                $name = \Str::slug($ro['label'], '');
                $columns = $ro['columns'];
                $getColName = Request::get($name.'-'.$columns[0]['name']);
                $count_input_data = ($getColName)?(count($getColName) - 1):-1;
                $child_array = [];
                $childtable = MITBooster::parseSqlTable($ro['table'])['table'];
                $fk = $ro['foreign_key'];

                if (!$repeatFlag) {
                    DB::table($childtable)->where($fk, $id)->delete();
                    $repeatFlag = true;
                }
                $lastId = MITBooster::newId($childtable);
                $childtablePK = MITBooster::pk($childtable);

                for ($i = 0; $i <= $count_input_data; $i++) {
                    $column_data = [];
                    $column_data[$childtablePK] = $lastId;
                    $column_data[$fk] = $id;
                    foreach ($columns as $col) {
                        $colname = $col['name'];
                        if ($col['type'] == 'money' || $col['type'] == 'number') {
                            $temp_data = Request::get($name.'-'.$colname)[$i];
                            $temp_data = str_replace(',', '', $temp_data);
                            $column_data[$colname] = $temp_data;
                            if ($column_data[$colname] == "") {
                                $column_data[$colname] = 0;
                            }
                        } else {
                            $column_data[$colname] = Request::get($name.'-'.$colname)[$i];
                        }
                    }
                    $child_array[] = $column_data;

                    $lastId++;
                }

                $child_array = array_reverse($child_array);
                DB::table($childtable)->insert($child_array);
            }

            if (isset($ro['type']) && ($ro['type'] == 'child')) {
                $name = \Str::slug($ro['label'], '');
                $columns = $ro['columns'];
                $getColName = Request::get($name.'-'.$columns[0]['name']);
                $count_input_data = ($getColName)?(count($getColName) - 1):-1;
                $child_array = [];
                $childtable = MITBooster::parseSqlTable($ro['table'])['table'];
                $fk = $ro['foreign_key'];


                DB::table($childtable)->where($fk, $id)->delete();
                $lastId = MITBooster::newId($childtable);
                $childtablePK = MITBooster::pk($childtable);

                for ($i = 0; $i <= $count_input_data; $i++) {
                    $column_data = [];
                    $column_data[$childtablePK] = $lastId;
                    $column_data[$fk] = $id;
                    foreach ($columns as $col) {
                        $colname = $col['name'];
                        if ($col['type'] == 'money' || $col['type'] == 'number') {
                            $temp_data = Request::get($name.'-'.$colname)[$i];
                            $temp_data = str_replace(',', '', $temp_data);
                            $column_data[$colname] = $temp_data;
                            if ($column_data[$colname] == "") {
                                $column_data[$colname] = 0;
                            }
                        } else {
                            $column_data[$colname] = Request::get($name.'-'.$colname)[$i];
                        }
                    }
                    $child_array[] = $column_data;

                    $lastId++;
                }

                $child_array = array_reverse($child_array);
                DB::table($childtable)->insert($child_array);
            }
        }
    }

    public function postActionSelected()
    {
        $this->mitLoader();
        $id_selected = Request::input('checkbox');
        $button_name = Request::input('button_name');

        if (!$id_selected) {
            return MITBooster::redirect($_SERVER['HTTP_REFERER'], trans("locale.alert_select_a_data"), 'warning');
        }

        if ($button_name == 'delete') {
            if (!MITBooster::isDelete()) {
                MITBooster::insertLog(trans("locale.log_try_delete_selected", ['module' => MITBooster::getCurrentModule()->name]));
                return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
            }

            $this->hook_before_delete($id_selected);
            if (Schema::hasColumn($this->table, 'deleted_at')) {
                DB::table($this->table)->whereIn($this->primary, $id_selected)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            } else {
                DB::table($this->table)->whereIn($this->primary, $id_selected)->delete();
            }

            //insert log
            MITBooster::insertLog(trans("locale.log_delete", [
                'name' => implode(',', $id_selected),
                'module' => MITBooster::getCurrentModule()->name
            ]));

            $this->hook_after_delete($id_selected);

            $message = trans("locale.alert_delete_selected_success");

            return redirect()->back()->with(['message_type' => 'success', 'message' => $message]);
        }

        $action = str_replace(['-', '_'], ' ', $button_name);
        $action = ucwords($action);
        $type = 'success';
        $message = trans("locale.alert_action", ['action' => $action]);

        if ($this->actionButtonSelected($id_selected, $button_name) === false) {
            $message = ! empty($this->alert['message']) ? $this->alert['message'] : 'Error';
            $type = ! empty($this->alert['type']) ? $this->alert['type'] : 'danger';
        }

        return redirect()->back()->with(['message_type' => $type, 'message' => $message]);
    }

    public function postUploadifive()
    {
        if (!empty($_FILES)) {
            $filename = MITBooster::uploadFile('Filedata');
            $id = db::table('mit_media')->insertGetId([
                'created_at' => date('Y-m-d H:i:s'),
                'filename' => $filename,
            ]);
            if ($filename != null) {
                $name = Request::get('name');
                $html = '
                    <div class="col-sm-3 py-1 pr-1">
                        <div class="thumbnail">
                            <a href="javascript:void(0)" onclick="deleteImage(this)" class="remove"><i class="fa fa-trash"></i> Delete Image</a>
                            <img src="'.asset($filename).'" />
                        </div>
                        <input type="hidden" name="'.$name.'[]" value="'.$id.'" />
                    </div>';
                echo $html;
            }
        }
    }
}
