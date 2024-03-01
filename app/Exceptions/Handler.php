<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['isStatus'=>false,'message' => 'Unauthenticate User'], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'))->with('error', 'Your Session Invialid .You are login in another device');
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
