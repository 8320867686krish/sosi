<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    protected function prepareException(Throwable $e)
    {
        
        if ($e instanceof TokenMismatchException) {
            $e = new HttpException(419, 'Your session has expired. Please refresh the page to continue using the system.', $e);
        }

        return parent::prepareException($e);
    }

    public function render($request, Throwable $exception)
    {

        if (!$request->header('upgrade-insecure-requests')) {
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'isStatus' => false,
                    'messgae' => 'Unauthenticate User'
                ], 401);
            }
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'isStatus' => false,
                'messgae' => 'Too many requests. Please try again later'
            ], 429);
        }

        return parent::render($request, $exception);
    }

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
