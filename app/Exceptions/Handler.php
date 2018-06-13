<?php

namespace App\Exceptions;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    protected $response;

    public function __construct(Container $container, Response $response)
    {
        $this->response = $response;
        parent::__construct($container);
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ('api' === $request->route()->getPrefix()) {
            if ($exception instanceof ValidationException) {
                return $this->response->errorUnprocessable($exception->validator->errors()->first());
            } elseif (method_exists($exception, 'getStatusCode') && $exception->getStatusCode() === 403) {
                return $this->response->errorForbidden(__('auth.not_permitted'));
            }
        }
        return parent::render($request, $exception);
    }
}
