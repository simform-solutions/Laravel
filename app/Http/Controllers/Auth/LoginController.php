<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
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

    use AuthenticatesUsers;

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
            if ('api' === $request->route()->getPrefix()) {
                return $this->response->errorUnprocessable(__('auth.account_inactive'));
            } else {
                throw ValidationException::withMessages([
                    $this->username() => [__('auth.account_inactive')],
                ]);
            }
        }
        if ('api' === $request->route()->getPrefix()) {
            auth()->logout();
            return $this->response->withItem($user, new UserTransformer, null, [], ['X-Session-Token' => encrypt(time())]);
        }
        return redirect()->intended($this->redirectTo);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        if ('api' === $request->route()->getPrefix()) {
            return $this->response->errorUnauthorized(__('auth.failed'));
        } else {
            throw ValidationException::withMessages([
                $this->username() => [__('auth.failed')],
            ]);
        }
    }
}
