<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;

class ServiceApiController extends ApiController
{
    public function init()
    {
        $this->table = 'services';
    }

    public function postSync()
    {
        try {
            $this->mitLoader();

            $name = Request::get('name');

            $data = DB::table($this->table)->where('name', $name)->first();
            if ($data == null) {
                $id = DB::table($this->table)->insertGetId([
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                $data = DB::table($this->table)->find($id);
            }
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postCommit()
    {
        try {
            $this->mitLoader();

            $name = Request::get('name');
            $last_sync = Request::get('last_sync');
            
            DB::table($this->table)->where('name', $name)->update([
                'last_sync' => $last_sync
            ]);
            $data = DB::table($this->table)->where('name', $name)->first();
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }
}
