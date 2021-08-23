<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class OvertimesApiController extends ApiController
{
    public function init()
    {
        $this->table = 'overtimes';
    }

    public function postFilter()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $params = Request::all();
            $data = db::table('overtimes')
                ->where('employee_id', $id)
                ->whereRaw("trans_date >= '".$params['startDate']."' and trans_date <= '".$params['endDate']."'")
                ->select("*", "trans_date as transdate")
                ->orderBy('trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postUnapproved()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $data = db::table('overtimes')
                ->where('employee_id', $id)
                ->whereRaw("(approval_1 != 2 and approval_2 = 0)")
                ->select("*", "trans_date as transdate")
                ->orderBy('trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postCreate()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $trans_date = Request::get('transdate');
            $start_hour = Request::get('start_hour');
            $hour = substr($start_hour, 0, 2);
            $minute = substr($start_hour, 3, 2);
            $start_hour = date('Y-m-d H:i:s', strtotime('+'.$hour.' hour +'.$minute.' minutes', strtotime($trans_date)));

            $end_hour = Request::get('end_hour');
            $hour = substr($end_hour, 0, 2);
            $minute = substr($end_hour, 3, 2);
            $end_hour = date('Y-m-d H:i:s', strtotime('+'.$hour.' hour +'.$minute.' minutes', strtotime($trans_date)));
            $reason = Request::get('reason');

            $data = [
                'created_at' => date('Y-m-d H:i:s'),
                'start_hour' => $start_hour,
                'end_hour' => $end_hour,
                'trans_date' => $trans_date,
                'multiply' => 0,
                'reason' => $reason,
                'approval_1' => 0,
                'approval_2' => 0,
                'employee_id' => $id,
            ];

            $employee = db::table('employees')->where('id', $id)->first();
            if ($employee->leader_id == 0) {
                $data['approval_1'] = 1;
            }
            
            db::table('overtimes')->insert($data);

            $data = db::table('overtimes')
                ->where('employee_id', $id)
                ->whereRaw("(approval_1 != 2 and approval_2 = 0)")
                ->select("*", "trans_date as transdate")
                ->orderBy('trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postDeleted()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = Request::get('id');
            db::table('overtimes')->where('id', $id)->delete();

            $id = $decoded->data->employee_id;
            $data = db::table('overtimes')
                ->where('employee_id', $id)
                ->whereRaw("(approval_1 != 2 and approval_2 = 0)")
                ->select("*", "trans_date as transdate")
                ->orderBy('trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postApproval()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $data = db::table('overtimes as a')
                ->join('employees as b', 'a.employee_id', 'b.id')
                ->where('b.leader_id', $id)
                ->where('a.approval_1', 0)
                ->select('a.id', 'a.trans_date as transdate', 'a.start_hour', 'a.end_hour', 'a.reason', 'b.photo')
                ->orderBy('a.trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postApprove()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = Request::get('id');
            $status = Request::get('status');
            db::table('overtimes')->where('id', $id)->update(['approval_1' => $status]);

            $id = $decoded->data->employee_id;
            $data = db::table('overtimes as a')
                ->join('employees as b', 'a.employee_id', 'b.id')
                ->where('b.leader_id', $id)
                ->where('a.approval_1', 0)
                ->select('a.id', 'a.trans_date as transdate', 'a.start_hour', 'a.end_hour', 'a.reason', 'b.photo')
                ->orderBy('a.trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }
}
