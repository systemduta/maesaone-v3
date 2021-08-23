<?php

namespace App\Http\Api;

use MITBooster;
use \Firebase\JWT\JWT;
use Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Mixtra\Controllers\ApiController;

class UserApiController extends ApiController
{
    private $is_employee = false;

    public function authenticate(array $credentials)
    {
        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return false;
        }

        // Checking User
        $this->is_employee = false;
        $user = DB::table('users')
            ->where("email", $credentials["username"])
            ->first();
        if ($user != null) {
            if (\Hash::check($credentials["password"], $user->password)) {
                return $this->createToken($user->id, $user->email, false);
            }
        }

        // Checking Employee
        $this->is_employee = true;
        $nik = substr($credentials["username"], 3, 6);
        $employee = DB::table('employees')
            ->where('end_working', null)
            ->where("nik", $nik)
            ->first();
        if ($employee != null) {
            // Checking Company ID
            $code = substr($credentials["username"], 0, 3);
            $company = DB::table('companies')
                ->where("id", $employee->company_id)
                ->first();
            if ($company == null) {
                return false;
            }
            // dump($employee->company_id);
            if ($code != $company->code) {
                return false;
            }
            // dd($company);
            if ($employee->password == null) {
                if ($credentials["password"] == '12345678') {
                    return $this->createToken($employee->id, $employee->nik, true);
                }
            } else {
                if (\Hash::check($credentials["password"], $employee->password)) {
                    return $this->createToken($employee->id, $employee->nik, false);
                }
            }
        }
    }

    private function createToken($id, $nik, $lessSecure)
    {
        $tokenId    = base64_encode(random_bytes(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt;                        // Adding 10 seconds
        $expire     = $notBefore + 86400;               // Adding 3600 seconds
        
        $payload = [
            'iat'  => $issuedAt,                        // Issued at: time when the token was generated
            'jti'  => $tokenId,                         // Json Token Id: an unique identifier for the token
            'nbf'  => $notBefore,                       // Not before
            'exp'  => $expire,                          // Expire
            'data' => [                                 // Data related to the signer user
                'employee_id'   => $id,
                'nik'           => $nik,
                'lessSecure'    => $lessSecure,
                'is_employee'    => $this->is_employee,
            ]
        ];

        return JWT::encode($payload, config("mixtra.api_secret"), 'HS512');
    }

    public function login()
    {
        $credentials = Request::all();
        try {
            if (! $token = $this->authenticate($credentials)) {
                return $this->set_error('invalid_credentials', 400);
            }
        } catch (\Exception $e) {
            return $this->set_error($e->getMessage(), 500);
        }

        $lessSecure = false;
        // dd($credentials);
        if ($credentials["password"] == '12345678') {
            $lessSecure = true;
        }

        $data = [
            'token' => $token,
            'lessSecure' => $lessSecure,
            'is_employee'=>$this->is_employee
        ];
        return $this->set_succeed($data);
    }
}
