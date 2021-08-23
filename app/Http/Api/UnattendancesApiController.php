<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class UnattendancesApiController extends ApiController
{
    public function init()
    {
        $this->table = 'unattendances';
    }

    public function postFilter()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $params = Request::all();
            $data = db::table('unattendances')
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
            $data = db::table('unattendances')
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
            $start_date = Request::get('start_date');
            $end_date = Request::get('end_date');
            $reason = Request::get('reason');

            $data = [
                'created_at' => date('Y-m-d H:i:s'),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'trans_date' => $trans_date,
                'unattendance_type' => 0,
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

            db::table('unattendances')->insert($data);

            $data = db::table('unattendances')
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
            db::table('unattendances')->where('id', $id)->delete();

            $id = $decoded->data->employee_id;
            $data = db::table('unattendances')
                ->where('employee_id', $id)
                ->whereRaw("(approval_1 != 2 and approval_2 = 0)")
                ->orderBy('trans_date', 'asc')
                ->select("*", "trans_date as transdate")
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
            $data = db::table('unattendances as a')
                ->join('employees as b', 'a.employee_id', 'b.id')
                ->where('b.leader_id', $id)
                ->where('a.approval_1', 0)
                ->select('a.id', 'a.trans_date as transdate', 'a.start_date', 'a.end_date', 'a.reason', 'b.photo')
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
            db::table('unattendances')->where('id', $id)->update(['approval_1' => $status]);

            $id = $decoded->data->employee_id;
            $data = db::table('unattendances as a')
                ->join('employees as b', 'a.employee_id', 'b.id')
                ->where('b.leader_id', $id)
                ->where('a.approval_1', 0)
                ->select('a.id', 'a.trans_date as transdate', 'a.start_date', 'a.end_date', 'a.reason', 'b.photo')
                ->orderBy('a.trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }
}
