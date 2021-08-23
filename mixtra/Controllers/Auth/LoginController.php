<?php

namespace Mixtra\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use MITBooster;
use Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('mixtra::auth.login');
    }

    // protected function attemptLogin(Request $request)
    // {
    //     return $this->guard()->attempt(
    //         $this->credentials($request),
    //         $request->filled('remember'));
    // }

    // protected function credentials(Request $request)
    // {
    //     return $request->only($this->username(), 'password');
    // }

    public function authenticated(Request $request, $user)
    {
        MITBooster::insertLog(trans("locale.log_login", ['email' => $user->email, 'ip' => \Request::server('REMOTE_ADDR')]));
    }

    public function logout(Request $request)
    {
        MITBooster::insertLog(trans("locale.log_logout", ['email' => Auth::user()->email]));

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
