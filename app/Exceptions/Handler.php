<?php

namespace App\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

use App\Facades\CustomJsend;

class Handler extends ExceptionHandler
{
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
        $this->renderable(function (TokenInvalidException $e, $request) {
            return CustomJsend::error('Invalid token', 401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return CustomJsend::error('Token has Expired', 401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return CustomJsend::error('Token not parsed', 401);
        });
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return CustomJsend::error('Too many requests', 429);
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return CustomJsend::error('Not found URL', 404);
        });
    }
}
