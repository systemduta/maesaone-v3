<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;

class CompanyApiController extends ApiController
{
    public function init()
    {
        $this->table = 'companies';
    }

    public function postSync()
    {
        try {
            $this->mitLoader();

            $name = Request::get('name');
            $external_id = Request::get('external_id');
            $service_id = Request::get('service_id');
            $is_deleted = Request::get('is_deleted');

            $data = DB::table($this->table)
                ->where('external_id', $external_id)
                ->where('service_id', $service_id)
                ->first();
            if ($data == null) {
                if (!$is_deleted) {
                    $id = DB::table($this->table)->insertGetId([
                        'name' => $name,
                        'external_id' => $external_id,
                        'service_id' => $service_id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $data = DB::table($this->table)->find($id);
                }
            } else {
                $data = DB::table($this->table)
                    ->where('external_id', $external_id)
                    ->where('service_id', $service_id)
                    ->update([
                        'name' => $name,
                        'deleted_at' => ($is_deleted ? date('Y-m-d H:i:s') : null),
                    ]);
                $data = DB::table($this->table)
                    ->where('external_id', $external_id)
                    ->where('service_id', $service_id)
                    ->first();
            }
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }
}
