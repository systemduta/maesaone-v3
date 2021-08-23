<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class SalaryApiController extends ApiController
{
    public function init()
    {
        $this->table = 'salaries';
    }

    public function postClear()
    {
        try {
            $this->mitLoader();

            $params = Request::all();

            $data = DB::table('salaries')
                ->where('year', $params['year'])
                ->where('month', $params['month'])
                ->where('service_id', $params['service_id'])
                ->get();
            foreach ($data as $item) {
                DB::table('allowances')->where('salary_id', $item->id)->delete();
                DB::table('deductions')->where('salary_id', $item->id)->delete();
            }

            DB::table($this->table)
                ->where('year', $params['year'])
                ->where('month', $params['month'])
                ->where('service_id', $params['service_id'])
                ->delete();

            return $this->set_succeed('Cleared');
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
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
            
            $data = DB::table($this->table)
                ->where('external_id', $params['external_id'])
                ->where('service_id', $params['service_id'])
                ->first();

            if ($data == null) {
                if (!$params['is_deleted']) {
                    $id = DB::table($this->table)->insertGetId([
                        'employee_id' => $employee_id,
                        'year' => $params['year'],
                        'month' => $params['month'],
                        'hks' => $params['hks'],
                        'hka' => $params['hka'],
                        'late' => $params['late'],
                        'salary' => $params['salary'],
                        'allowance' => $params['allowance'],
                        'deduction' => $params['deduction'],
                        'loan' => $params['loan'],
                        'saving' => $params['saving'],
                        'rounding' => $params['rounding'],
                        'netto' => $params['netto'],
                        'external_id' => $params['external_id'],
                        'service_id' => $params['service_id'],
                        'created_at' => $params['created_at'],
                    ]);

                    foreach ($params['allowance_data'] as $item) {
                        DB::table('allowances')->insertGetId([
                            'salary_id' => $id,
                            'name' => $item['name'],
                            'amount' => $item['amount'],
                            'created_at' => $params['created_at'],
                        ]);
                    }

                    foreach ($params['deduction_data'] as $item) {
                        DB::table('deductions')->insertGetId([
                            'salary_id' => $id,
                            'name' => $item['name'],
                            'amount' => $item['amount'],
                            'created_at' => $params['created_at'],
                        ]);
                    }
                }

                $data = DB::table($this->table)->find($id);
            } else {
                DB::table($this->table)
                    ->where('external_id', $params['external_id'])
                    ->where('service_id', $params['service_id'])
                    ->update([
                        'employee_id' => $employee_id,
                        'year' => $params['year'],
                        'month' => $params['month'],
                        'hks' => $params['hks'],
                        'hka' => $params['hka'],
                        'late' => $params['late'],
                        'salary' => $params['salary'],
                        'allowance' => $params['allowance'],
                        'deduction' => $params['deduction'],
                        'loan' => $params['loan'],
                        'saving' => $params['saving'],
                        'rounding' => $params['rounding'],
                        'netto' => $params['netto'],
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
}
