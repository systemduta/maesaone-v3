<?php

namespace Mixtra\Middleware;

use Closure;
use MITBooster;
use Request;

class ApiAgent
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
        // Validate User Agent
        $allowedUserAgent = config('mixtra.agent_allowed');
        $user_agent = Request::header('User-Agent');
        if ($allowedUserAgent && count($allowedUserAgent)) {
            $userAgentValid = false;
            foreach ($allowedUserAgent as $a) {
                if (stripos($user_agent, $a) !== false) {
                    $userAgentValid = true;
                    break;
                }
            }
            if (!$userAgentValid) {
                $result = [
                    'error' => 'Device Agent is Invalid',
                    'data' => null,
                ];
                return response()->json($result, 400);
            }
        }

        return $next($request);
    }
}
