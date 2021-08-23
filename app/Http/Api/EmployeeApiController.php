<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class EmployeeApiController extends ApiController
{
    public function init()
    {
        $this->table = 'employees';
    }

    public function postSync()
    {
        try {
            $this->mitLoader();

            $params = Request::all();

            $company_id = 0;
            $company = db::table('companies')
                ->where('name', $params['company'])
                ->where('service_id', $params['service_id'])
                ->first();
            if ($company != null) {
                $company_id = $company->id;
            }

            $leader_id = 0;
            $leader = db::table('employees')
                ->where('external_id', $params['leader_id'])
                ->where('service_id', $params['service_id'])
                ->first();
            if ($leader != null) {
                $leader_id = $leader->id;
            }
            
            $data = DB::table($this->table)
                ->where('external_id', $params['external_id'])
                ->where('service_id', $params['service_id'])
                ->first();
                
            $max = db::table('employees')->whereRaw("year(start_working) = '".date('Y', strtotime($params["start_working"]))."'")->count();
            $nik = date('y', strtotime($params['start_working'])).sprintf("%04d", ($max+1));

            if ($data == null) {
                // Check apakah sudah ada nama dan dob yang sama persis
                $data = db::table('employees')
                    ->where('name', $params['name'])
                    ->where('date_of_birth', $params['date_of_birth'])
                    ->first();
            }

            if ($data == null) {
                // Jika masih blm ketemu maka harus insert baru
                if (!$params['is_deleted']) {
                    $id = DB::table($this->table)->insertGetId([
                        'nik' => $nik,
                        'old_nik' => $params['nik'],
                        'name' => $params['name'],
                        'mobile' => $params['mobile'],
                        'email' => $params['email'],
                        'start_working' => $params['start_working'],
                        'end_working' => $params['end_working'],
                        'date_of_birth' => $params['date_of_birth'],
                        
                        'leader_Id' => $leader_id,
                        'company_id' => $company_id,
                        'branch' => $params['branch'],
                        'department' => $params['department'],
                        'title' => $params['title'],
                        'employee_type' => $params['employee_type'],
                        'group_employee' => $params['group_employee'],
                        'class' => $params['class_name'],
                        
                        'external_id' => $params['external_id'],
                        'service_id' => $params['service_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                $data = DB::table($this->table)->find($id);
            } else {
                DB::table($this->table)
                    ->where('id', $data->id)
                    ->update([
                        'name' => $params['name'],
                        'mobile' => $params['mobile'],
                        'email' => $params['email'],
                        'start_working' => $params['start_working'],
                        'end_working' => $params['end_working'],
                        'date_of_birth' => $params['date_of_birth'],
                        
                        'leader_Id' => $leader_id,
                        'company_id' => $company_id,
                        'branch' => $params['branch'],
                        'department' => $params['department'],
                        'title' => $params['title'],
                        'employee_type' => $params['employee_type'],
                        'group_employee' => $params['group_employee'],
                        'class' => $params['class_name'],
                        
                        'external_id' => $params['external_id'],
                        'service_id' => $params['service_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'deleted_at' => ($params['is_deleted'] ? date('Y-m-d H:i:s') : null),
                    ]);
                $data = DB::table($this->table)
                    ->where('id', $data->id)
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

            $params = Request::all();

            $data = DB::table($this->table)
                ->where('service_id', $params['service_id'])
                ->whereRaw("updated_at > '".date('Y-m-d H:i:s', strtotime($params["last_sync"]))."'")
                ->get();
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }

    public function postAvatar()
    {
        try {
            $this->mitLoader();

            $params = Request::all();

            $employee = db::table('employees')
                ->where('external_id', $params['external_id'])
                ->where('service_id', $params['service_id'])
                ->first();
            
            if ($employee != null) {
                $filename = $employee->photo;
                Storage::delete($filename);

                $filename = preg_replace("/uploads/", "uploads_thumbnail", $filename);
                Storage::delete($filename);

                if ($file = MITBooster::uploadFile('image', true, 128, 128)) {
                    db::table($this->table)
                        ->where('external_id', $params['external_id'])
                        ->where('service_id', $params['service_id'])
                        ->update(['photo'=>$file]);
                }
            }

            $data = DB::table($this->table)
                ->where('external_id', $params['external_id'])
                ->where('service_id', $params['service_id'])
                ->first();

        
            return $this->set_succeed($data);
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }
    }
}
