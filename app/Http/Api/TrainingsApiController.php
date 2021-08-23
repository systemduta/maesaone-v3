<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class TrainingsApiController extends ApiController
{
    public function init()
    {
        $this->table = 'trainings';
    }

    public function postFilter()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $params = Request::all();
            $data = db::table('trainings')
                ->where('employee_id', $id)
                // ->whereRaw("trans_date >= '".$params['startDate']."' and trans_date <= '".$params['endDate']."'")
                ->select("*", "trans_date as transdate")
                ->orderBy('trans_date', 'asc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }
}
