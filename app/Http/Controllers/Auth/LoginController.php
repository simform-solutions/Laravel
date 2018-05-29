<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
use App\User;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    use AuthenticatesUsers {
        validateLogin as defaultValidateLogin;
        attemptLogin as defaultAttemptLogin;
        username as defaultUsername;
        sendFailedLoginResponse as defaultSendFailedLoginResponse;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * @var Response
     */
    protected $response;

    /**
     * Create a new controller instance.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     * @throws ValidationException
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_active) {
            auth()->logout();
            throw ValidationException::withMessages([
                $this->username() => [__('auth.account_inactive')],
            ]);
        }

        if ('api' === $request->route()->getPrefix()) {
            auth()->logout();
            return $this->response->withItem($user, new UserTransformer, null, [], ['X-Session-Token' => encrypt(time())]);
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        if ($request->request->has('facebook_id')) {
            $this->validate($request, ['facebook_id' => 'required|numeric']);
        } else {
            $this->defaultValidateLogin($request);
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if ($request->request->has('facebook_id')) {
            if ($user = User::whereFacebookId($request->get('facebook_id'))->first()) {
                $this->guard()->login($user);
                return $this->guard()->check();
            }
        }

        return $this->defaultAttemptLogin($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->request->has('facebook_id')) {
            return $this->response->withArray([]);
        }

        return $this->defaultSendFailedLoginResponse($request);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        if ('api' === \request()->route()->getPrefix()) {
            return 'mobile_number';
        }

        return $this->defaultUsername();
    }
}
