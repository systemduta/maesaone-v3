<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class AttendanceApiController extends ApiController
{
    public function init()
    {
        $this->table = 'attendances';
    }

    public function postSync()
    {
        try {
            $this->mitLoader();

            $params = Request::all();

            $employee_id = 0;
            $employee = db::table('employees')
                ->where('nik', $params['nik'])
                ->first();
            if ($employee != null) {
                $employee_id = $employee->id;
            }

            if ($employee_id == 0) {
                return $this->set_succeed('Invalid EmployeeID');
            }

            $data = DB::table($this->table)
                ->where('external_id', $params['external_id'])
                ->where('service_id', $params['service_id'])
                ->first();
                
            if ($data == null) {
                if (!$params['is_deleted']) {
                    $id = DB::table($this->table)->insertGetId([
                        'employee_id' => $employee_id,
                        'trans_date' => $params['trans_date'],
                        'check_time' => $params['check_time'],
                        'attendance_type' => $params['attendance_type'],
                        'external_id' => $params['external_id'],
                        'service_id' => $params['service_id'],
                        'created_at' => $params['created_at'],
                    ]);
                }

                $data = DB::table($this->table)->find($id);
            } else {
                DB::table($this->table)
                    ->where('external_id', $params['external_id'])
                    ->where('service_id', $params['service_id'])
                    ->update([
                        'employee_id' => $employee_id,
                        'trans_date' => $params['trans_date'],
                        'check_time' => $params['check_time'],
                        'attendance_type' => $params['attendance_type'],
                        'external_id' => $params['external_id'],
                        'service_id' => $params['service_id'],
                        'updated_at' => $params['updated_at'],
                        'deleted_at' => ($params['is_deleted'] ? date('Y-m-d H:i:s') : null),
                    ]);
                $data = DB::table($this->table)
                    ->where('external_id', $params['external_id'])
                    ->where('service_id', $params['service_id'])
                    ->first();
            }
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postPull()
    {
        try {
            $this->mitLoader();

            $data = DB::table('attendances as a')
                ->join('employees as b', 'a.employee_id', 'b.id')
                ->select("a.*", "b.nik")
                ->whereNull("a.external_id")
                ->get();
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postPush()
    {
        try {
            $this->mitLoader();

            $params = Request::all();

            DB::table('attendances')->where('id', $params['id'])->update([
                'external_id' => $params['external_id']
            ]);

            $data = DB::table('attendances as a')->find($params['id']);
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }
}
