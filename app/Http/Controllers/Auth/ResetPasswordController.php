<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use EllipseSynergie\ApiResponse\Laravel\Response;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords {
        rules as defaultRules;
        credentials as defaultCredentials;
        sendResetResponse as defaultSendResetResponse;
        sendResetFailedResponse as defaultSendResetFailedResponse;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
        $this->middleware('guest');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        if ('api' === \request()->route()->getPrefix()) {
            return [
                'mobile_number' => 'required|string|max:20|exists:users',
                'password' => 'required|confirmed|min:6',
                'token' => 'required|string'
            ];
        }
        return $this->defaultRules();
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if ('api' === \request()->route()->getPrefix()) {
            return $request->only(
                'mobile_number', 'password', 'password_confirmation', 'token'
            );
        }
        return $this->defaultCredentials($request);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse($response)
    {
        if ('api' === \request()->route()->getPrefix()) {
            $user = auth()->user();
            auth()->logout();
            return $this->response->withItem($user, new UserTransformer, null, [], ['X-Session-Token' => encrypt(time())]);
        }

        return $this->defaultSendResetResponse($response);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        if ('api' === \request()->route()->getPrefix()) {
            return $this->response->errorUnauthorized(__($response));
        }
        return $this->defaultSendResetFailedResponse($request, $response);
    }
}
