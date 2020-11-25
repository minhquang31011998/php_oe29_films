<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password], $request->has('remember'))) {
            if (Auth::user()->is_active == config('config.status_active')) {
                if (Auth::user()->role == config('config.role_admin')) {
                    return redirect()->route('backend.home');
                } elseif (Auth::user()->role == config('config.role_user')) {
                    return redirect()->route('frontend.home');
                }
            } else {
                Auth::logout();
                return view('auth.login')->with('error', trans('auth_block'));
            }
        }

        return view('auth.login')->with('error', trans('auth_wrong'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('frontend.home');
    }
}
