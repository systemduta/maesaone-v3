<?php

namespace Mixtra\Controllers;

use DateTime;
use DB;
use Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public $table;

    public function mitLoader()
    {
        $this->init();
    }

    public function getIndex()
    {
        try {
            $this->mitLoader();

            $data = DB::table($this->table)->get();
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $this->mitLoader();

            $data = DB::table($this->table)->where('id', $id)->first();
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postCreate()
    {
        try {
            $this->mitLoader();

            $data = Request::all();
            if (!isset($data['created_at'])) {
                $data['created_at'] = date('Y-m-d H:i:s');
            }
        
            $id = DB::table($this->table)->insertGetId($data);
            $data['id'] = $id;
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postUpdate()
    {
        try {
            $this->mitLoader();

            $data = Request::all();
            if (!isset($data['updated_at'])) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }
        
            DB::table($this->table)->where('id', $data['id'])->update($data);
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postDelete()
    {
        try {
            $this->mitLoader();

            $id = Request::get('id');

            DB::table($this->table)->where('id', $id)->delete();
            return $this->set_succeed($id);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    protected function set_succeed($data)
    {
        return $this->set_result($data, null, 200);
    }

    protected function set_error($error, $status)
    {
        return $this->set_result(null, $error, $status);
    }

    protected function set_result($data, $error, $status)
    {
        $result = [
            'error' => $error,
            'data' => $data,
        ];

        return response()->json($result, $status);
    }
}
