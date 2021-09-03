<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class AttendancesApiController extends ApiController
{
    public function init()
    {
        $this->table = 'attendances';
    }

    public function postFilter()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $params = Request::all();
            $data = db::table('attendances')
                ->where('employee_id', $id)
                ->whereRaw("check_time >= '".$params['startDate']."' and check_time <= '".$params['endDate']."'")
                ->selectRaw("trans_date as transdate, attendance_type, DATE_FORMAT(check_time, '%H:%i') as check_time")
                ->orderBy('trans_date', 'asc')
                ->orderBy('check_time', 'asc')
                ->distinct()
                ->get();
            foreach ($data as $index => $item) {
                $item->id = $index+1;
            }
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postToday()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');

            $data = db::table('attendances')
                ->where('employee_id', $id)
                ->whereRaw("check_time >= '".$start_date."' and check_time <= '".$end_date."'")
                ->selectRaw("trans_date as transdate, check_time, attendance_type")
                ->orderBy('trans_date', 'asc')
                ->orderBy('check_time', 'asc')
                ->distinct()
                ->get();
            foreach ($data as $index => $item) {
                $item->id = $index+1;
            }
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postClock()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $type = Request::get('type');
            $location = Request::get('location');

            db::table('attendances')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'trans_date' => date('Y-m-d'),
                'check_time' => date('Y-m-d H:i:s'),
                'attendance_type' => $type,
                'location' => $location,
                'employee_id' => $id,
            ]);

            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');

            $data = db::table('attendances')
                ->where('employee_id', $id)
                ->whereRaw("check_time >= '".$start_date."' and check_time <= '".$end_date."'")
                ->selectRaw("trans_date as transdate, check_time, attendance_type")
                ->orderBy('trans_date', 'asc')
                ->orderBy('check_time', 'asc')
                ->distinct()
                ->get();
            foreach ($data as $index => $item) {
                $item->id = $index+1;
            }
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }
}
