<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
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

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $throwable
     * @return void
     */
    public function report(Throwable $throwable)
    {
        parent::report($throwable);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if (request()->is('api/*') || request()->is('ajax/*')) {
            $this->handleJsonResponse($request, $exception);
        }

        return $this->handleHtmlErrors($request, $exception);
    }

    /**
     * Render an exception into an HTTP response for JSON requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleJsonResponse($request, $exception)
    {
        $e = $this->prepareException($exception);

        if ($e instanceof NotFoundHttpException) {
            $e = new NotFoundHttpException('Page not found', null, 404);
        }

        return $this->prepareJsonResponse($request, $e);
    }

    /**
     * Render an exception into an HTTP response for standard web requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\Response
     */
    public function handleHtmlErrors($request, $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

        return parent::render($request, $exception);
    }
}
