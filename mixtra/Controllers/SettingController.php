<?php

namespace Mixtra\Controllers;

// use MITBooster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use MITBooster;

class SettingController extends MITController
{
    public function init()
    {
        $this->table = 'mit_settings';
        $this->title_field = 'name';

        $this->columns = [];
    }

    public function getShow()
    {
        $this->mitLoader();
        $group = 'Application';

        if (! MITBooster::isSuperadmin()) {
            MITBooster::insertLog(trans("locale.log_try_view", ['name' => 'Settings', 'module' => $group]));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        $data['page_title'] = $group;
        return view('mixtra::settings', $data);
    }

    public function postSaveSetting()
    {
        if (! MITBooster::isSuperadmin()) {
            MITBooster::insertLog(trans("locale.log_try_view", ['name' => 'Setting', 'module' => 'Setting']));
            return MITBooster::redirect(MITBooster::adminPath(), trans('locale.denied_access'));
        }

        $group = Request::get('group_setting');
        $setting = DB::table('mit_settings')->where('group_setting', $group)->get();

        foreach ($setting as $set) {
            $name = $set->name;
            $content = Request::get($set->name);

            $setting = DB::table('mit_settings')->where('name', $set->name)->first();
            if ($setting->content_input_type == 'upload_image') {
                $param = Request::get($name);
                if ($param == null || $param == '' || Request::hasFile($name)) {
                    Storage::delete($setting->content);
                }
            }

            if (Request::hasFile($name)) {
                $error = null;
                if ($set->content_input_type == 'upload_image') {
                    $error = $this->valid([$name => 'image|max:1000'], 'view');
                }
                if ($error != null) {
                    $res = redirect()->back()->with("errors", $error)->with([
                        'message' => trans('locale.alert_validation_error', ['error' => implode(', ', $error)]),
                        'message_type' => 'warning',
                    ])->withInput();
                    \Session::driver()->save();
                    $res->send();
                    exit;
                }

                $file = Request::file($name);
                $ext = $file->getClientOriginalExtension();
                
                //Create Directory Monthly
                $directory = 'uploads/'.date('Y-m');
                Storage::makeDirectory($directory);

                //Move file to storage
                $filename = md5(\Str::random(5)).'.'.$ext;
                $storeFile = Storage::putFileAs($directory, $file, $filename);
                if ($storeFile) {
                    $content = $directory.'/'.$filename;
                }
            }
            DB::table('mit_settings')->where('name', $set->name)->update(['content' => $content]);
        }

        return redirect()->back()->with(['message' => 'Your setting has been saved !', 'message_type' => 'success']);
    }

    public static function valid($arr = [], $type = 'json')
    {
        $input_arr = Request::all();

        foreach ($arr as $a => $b) {
            if (is_int($a)) {
                $arr[$b] = 'required';
            } else {
                $arr[$a] = $b;
            }
        }

        $validator = Validator::make($input_arr, $arr);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            if ($type == 'json') {
                $result = [];
                $result['api_status'] = 0;
                $result['api_message'] = implode(', ', $message);
                $res = response()->json($result, 200);
                $res->send();
                exit;
            } else {
                return $message;
            }
        }
    }
}
