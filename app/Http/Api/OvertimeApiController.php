<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class OvertimeApiController extends ApiController
{
    public function init()
    {
        $this->table = 'overtimes';
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
            $start_hour = Request::get('start_hour');
            $hour = substr($start_hour, 0, 2);
            $minute = substr($start_hour, 3, 2);
            $start_hour = date('Y-m-d H:i:s', strtotime('+'.$hour.' hour +'.$minute.' minutes', strtotime($date)));

            $end_hour = Request::get('end_hour');
            $hour = substr($end_hour, 0, 2);
            $minute = substr($end_hour, 3, 2);
            $end_hour = date('Y-m-d H:i:s', strtotime('+'.$hour.' hour +'.$minute.' minutes', strtotime($date)));
            
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
                        'trans_date' => $params['trans_date'],
                        'start_hour' => $start_hour,
                        'end_hour' => $end_hour,
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
                        'trans_date' => $params['trans_date'],
                        'start_hour' => $start_hour,
                        'end_hour' => $end_hour,
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

            $data = DB::table('overtimes as a')
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

            DB::table('overtimes')->where('id', $params['id'])->update([
                'external_id' => $params['external_id']
            ]);

            $data = DB::table('overtimes as a')->find($params['id']);
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }
}
