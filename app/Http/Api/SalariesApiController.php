<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class SalariesApiController extends ApiController
{
    public function init()
    {
        $this->table = 'salaries';
    }

    public function getMySalary()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $data = db::table('salaries')
                ->where('employee_id', $id)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
            return $this->set_succeed($data);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function getLatest()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $data = db::table('salaries')
                ->where('employee_id', $id)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(3)->get();

            foreach ($data as $item) {
                $allowance = db::table('allowances')
                    ->where('salary_id', $item->id)
                    ->get();
                $item->detailAllowance = $allowance;
                
                $deduction = db::table('deductions')
                    ->where('salary_id', $item->id)
                    ->get();
                $item->detailDeduction = $deduction;
            }

            return $this->set_succeed($data, $allowance, $deduction);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function getAllowanceDetail($id)
    {
        $data = db::table('allowances')
                ->where('salary_id', $id)
                ->get();
        return $this->set_succeed($data);
    }

    public function getDeductionDetail($id)
    {
        $data = db::table('deductions')
                ->where('salary_id', $id)
                ->get();
        return $this->set_succeed($data);
    }
}
