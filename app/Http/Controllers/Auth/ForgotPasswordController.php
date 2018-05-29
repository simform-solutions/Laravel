<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use EllipseSynergie\ApiResponse\Laravel\Response;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails {
        sendResetLinkEmail as defaultSendResetLinkEmail;
    }

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        if ('api' === \request()->route()->getPrefix()) {
            $this->validateMobile($request);
            if ($user = User::whereMobileNumber($request->get('mobile_number'))->first()) {
                return $this->response->withArray([
                    'token' => $this->broker()->getRepository()->create($user)
                ]);
            }
            throw ValidationException::withMessages([
                'mobile_number' => [__('validation.custom.exists.mobile_number')],
            ]);
        }
        return $this->defaultSendResetLinkEmail($request);
    }

    /**
     * Validate the mobile for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateMobile(Request $request)
    {
        $this->validate($request, [
            'mobile_number' => 'required|string|max:20|phone',
            'mobile_number_country' => 'required_with:mobile_number'
        ]);
    }
}
