<?php

namespace App\Http\Api;

use Mixtra\Controllers\ApiController;
use Request;
use DB;
use MITBooster;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeesApiController extends ApiController
{
    public function init()
    {
        $this->table = 'employees';
    }

    public function getMe()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $lessSecure = $decoded->data->lessSecure;
            return $this->employee($id, $lessSecure);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postProfile()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $params = Request::all();
            db::table('employees')
                ->where('id', $id)
                ->update([
                    'email'=>$params['email'],
                    'mobile'=>$params['mobile'],
                    'title'=>$params['position']
                ]);
                
            return $this->employee($id);
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postMyAvatar()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);
            
            $id = $decoded->data->employee_id;
            $employee = db::table('employees')
                ->where('id', $id)
                ->first();
        
            if ($employee != null) {
                $filename = $employee->photo;
                Storage::delete($filename);
    
                $filename = preg_replace("/uploads/", "uploads_thumbnail", $filename);
                Storage::delete($filename);

                if ($file = MITBooster::uploadFile('image', true, 512, 512)) {
                    db::table('employees')
                    ->where('id', $id)
                    ->update(['photo'=>$file]);
                }
                return $this->employee($id);
            }
        }
        return $this->set_error('invalid_token', 400);
    }

    public function postPassword()
    {
        $authorization = Request::header('Authorization');
        if (substr($authorization, 0, 7) == 'Bearer ') {
            $token = str_replace('Bearer ', '', $authorization);
            $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);

            $id = $decoded->data->employee_id;
            $params = Request::all();
            
            $employee = db::table('employees')->where('id', $id)->first();
            if ($employee->password == null) {
                if ($params["current"] != '12345678') {
                    return $this->set_error('Invalid Current Password', 400);
                }
            } else {
                if (\Hash::check($params['current'], $employee->password) == false) {
                    return $this->set_error('Invalid Current Password', 400);
                }
            }

            if ($params['password'] != $params['confirm']) {
                return $this->set_error('Invalid Confirm Password', 400);
            }

            $password = Hash::make($params['password']);
            db::table('employees')
                ->where('id', $id)
                ->update(['password'=>$password]);

            return $this->employee($id);
        }
        return $this->set_error('invalid_token', 400);
    }

    private function employeeByNIK($nik)
    {
        $data = db::table('employees')
            ->join('companies', 'employees.company_id', 'companies.id')
            ->where('employees.nik', $nik)
            ->select('employees.id', 'employees.nik', 'employees.name', 'employees.position', 'employees.photo', 'employees.position', 'employees.email', 'employees.mobile', 'companies.name as company')
            ->first();
        return $this->set_succeed($data);
    }

    private function employee($id, $lessSecure = false)
    {
        $data = db::table('employees')
            ->join('companies', 'employees.company_id', 'companies.id')
            ->where('employees.id', $id)
            ->select('employees.id', 'employees.nik', 'employees.name', 'employees.title as position', 'employees.photo', 'employees.email', 'employees.mobile', 'companies.name as company')
            ->first();

        $token = $this->createToken($data->id, $data->nik, $lessSecure);

        // $overtime = db::table('overtimes as a')
        //     ->join('employees as b', 'a.employee_id', 'b.id')
        //     ->where('b.leader_id', $id)
        //     ->where('a.approval_1', 0)
        //     ->select('a.id', 'a.transdate', 'a.start_hour', 'a.end_hour', 'a.reason', 'b.photo')
        //     ->orderBy('a.transdate', 'asc')
        //     ->count();

        // $unattendance = db::table('unattendances as a')
        //     ->join('employees as b', 'a.employee_id', 'b.id')
        //     ->where('b.leader_id', $id)
        //     ->where('a.approval_1', 0)
        //     ->select('a.id', 'a.transdate', 'a.start_date', 'a.end_date', 'a.reason', 'b.photo')
        //     ->orderBy('a.transdate', 'asc')
        //     ->count();

        // $task = $overtime + $unattendance;
        $task = 0;
        return $this->set_succeed(['data'=>$data, 'lessSecure'=>$lessSecure, 'token'=>$token, 'task'=>$task]);
    }

    private function createToken($id, $nik, $lessSecure)
    {
        $tokenId    = base64_encode(random_bytes(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt;                    // Adding 10 seconds
        $expire     = $notBefore + 86400;            // Adding 3600 seconds
        
        $payload = [
            'iat'  => $issuedAt,                    // Issued at: time when the token was generated
            'jti'  => $tokenId,                     // Json Token Id: an unique identifier for the token
            'nbf'  => $notBefore,                   // Not before
            'exp'  => $expire,                      // Expire
            'data' => [                             // Data related to the signer user
                'employee_id'   => $id,             // userid from the users table
                'nik'           => $nik,            // username from the users table
                'lessSecure'    => $lessSecure,     // username from the users table
            ]
        ];

        return JWT::encode($payload, config("mixtra.api_secret"), 'HS512');
    }
}
