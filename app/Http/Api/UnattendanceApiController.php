<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class UnattendanceApiController extends ApiController
{
    public function init()
    {
        $this->table = 'unattendances';
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

            $date = Request::get('trans_date');
            $start_date = Request::get('start_date');
            $end_date = Request::get('end_date');
            
            $approval1 = 0;
            if ($params['approval_1'] != null && $params['approval_1'] != 'False') {
                $approval1 = 1;
            }
            $approval2 = 0;
            if ($params['approval_2'] != null && $params['approval_2'] != 'False') {
                $approval2 = 1;
            }

            if ($data == null) {
                if (!$params['is_deleted']) {
                    $id = DB::table($this->table)->insertGetId([
                        'employee_id' => $employee_id,
                        'trans_date' => $date,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'unattendance_type' => $params['unattendance_type'],
                        'multiply' => $params['multiply'],
                        'reason' => $params['reason'],
                        'approval_1' => $approval1,
                        'approval_2' => $approval2,
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
                        'trans_date' => $date,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'unattendance_type' => $params['unattendance_type'],
                        'multiply' => $params['multiply'],
                        'reason' => $params['reason'],
                        'approval_1' => $approval1,
                        'approval_2' => $approval2,
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

            $data = DB::table('unattendances as a')
                ->join('employees as b', 'a.employee_id', 'b.id')
                ->select("a.*", "b.nik")
                ->where('approval_1', 1)
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

            DB::table('unattendances')->where('id', $params['id'])->update([
                'external_id' => $params['external_id']
            ]);

            $data = DB::table('unattendances as a')->find($params['id']);
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }
}
