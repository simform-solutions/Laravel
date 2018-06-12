<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Contracts\Encryption\DecryptException;

class AuthenticateUser
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $authDateTime = $request->header('Auth-Datetime');
            $authToken = $request->header('Authorization');

            if ($authDateTime && $authToken) {
                $keys = \explode(' ', $authToken);
                if (\count($keys) === 2 && $user = User::whereLastLoginAt($authDateTime)->find(\decrypt(\trim($keys[1])))) {
                    \auth()->login($user);
                    return $next($request);
                }
            }
        } catch (DecryptException $e) {
            return $this->response->errorUnprocessable(__('auth.invalid_session'));
        }

        return $this->response->errorUnauthorized(__('auth.unauthorized_access'));
    }
}
