<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
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
     * Render an exception into an HTTP response.
     * Override to avoid proxy validation issues in production.
     */
    public function render($request, Throwable $e)
    {
        // Convert non-HTTP exceptions to HTTP exceptions
        if (!$this->isHttpException($e)) {
            $e = new \Symfony\Component\HttpKernel\Exception\HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Server Error',
                $e
            );
        }

        // In production, skip Response::prepare() which triggers isSecure()
        // and just return a simple JSON or text response
        if (app()->environment('production')) {
            return response(
                json_encode([
                    'error' => $this->isHttpException($e) ? $e->getStatusCode() : 500
                ]),
                $this->isHttpException($e) ? $e->getStatusCode() : 500,
                ['Content-Type' => 'application/json']
            );
        }

        // In development, use the default handler
        return parent::render($request, $e);
    }
}
