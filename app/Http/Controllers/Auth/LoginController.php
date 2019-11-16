<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        //
        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();

            return response()->json([
                                        'token' => $user->api_token,
                                        'token_type' => 'bearer',
                                        'expires_at' => $user->expires_at->format('Y-m-d H:i:s')
                                    ], 200);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = $user->expires_at = null;
            $user->save();
        }

        return response()->json(null, 200);




  //      curl --location --request POST "http://localhost:8000/api/logout" \
  //--header "Accept: application/json" --header "Authorization:Bearer kf9Nnu90HkaXjkjy2gJWkDsplzuFezYFwDWoqY087p8YnpiLlkRvPDixBbu1"
    }
}
