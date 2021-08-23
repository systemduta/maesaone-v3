<?php

namespace Mixtra\Middleware;

use Closure;
use MITBooster;
use Request;
use \Firebase\JWT\JWT;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Validate Authentication
            $authorization = Request::header('Authorization');
            if (substr($authorization, 0, 7) == 'Bearer ') {
                $token = str_replace('Bearer ', '', $authorization);
                $decoded = JWT::decode($token, config("mixtra.api_secret"), ['HS512']);
                return $next($request);
            }
            $result = [
                'error' => 'Token is Invalid',
                'data' => null,
            ];
            return response()->json($result, 400);
        } catch (\Exception $e) {
            $result = [
                'error' => $e->getMessage(),
                'data' => null,
            ];
            return response()->json($result, 500);
        }
    }
}
