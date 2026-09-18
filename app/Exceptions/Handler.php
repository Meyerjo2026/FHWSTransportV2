<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Prepare exception for rendering.
     * Override to avoid isSecure() check which fails with proxy config.
     */
    protected function prepareResponse($request, Throwable $e)
    {
        if (!$this->isHttpException($e)) {
            $e = new \Symfony\Component\HttpKernel\Exception\HttpException(500, 'Server Error', $e);
        }

        // Don't call Response->prepare() which triggers isSecure() with broken proxy config
        // Just return a basic response
        return response(
            $this->renderHttpException($e),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : []
        );
    }
}
