<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\PostLoginAuthRequest;

use Auth;
use Flash;
use JsValidator;
use Request;

use App\Repositories\AuditRepository as Audit;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    private $redirectPath = '/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        return;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    /*protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'username' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }*/

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    /*protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        Flash::success("Welcome" . $user->first_name . ", your user has been created");

        return $user;
    }*/

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\PostLoginAuthRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(PostLoginAuthRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {

            $user = Auth::user();
            // Allow only if user is root or enabled.
            if ( ('root' == $user->username) || ($user->enabled) )
            {
                Audit::log(Auth::user()->id, request()->ip(), trans('general.audit-log.category-login'), trans('general.audit-log.msg-login-success', ['username' => $user->username]));

                Flash::success("Welcome " . Auth::user()->first_name);
                return redirect()->intended($this->redirectPath());
            }
            else
            {
                Audit::log(null, request()->ip(), trans('general.audit-log.category-login'), trans('general.audit-log.msg-forcing-logout', ['username' => $credentials['username']]));

                Auth::logout();
                return redirect(route('login'))
                    ->withInput($request->only('username', 'remember'))
                    ->withErrors([
                        'username' => trans('admin/users/general.error.login-failed-user-disabled'),
                    ]);
            }
        }

        Audit::log(null, request()->ip(), trans('general.audit-log.category-login'), trans('general.audit-log.msg-login-failed', ['username' => $credentials['username']]));

        return redirect($this->loginPath())
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        $page_title = "Login";

        return view('auth.login', compact('page_title'));
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public function getRegister()
    {
        $page_title = "Register";

        return view('auth.register', compact('page_title'));
    }
    */

}
