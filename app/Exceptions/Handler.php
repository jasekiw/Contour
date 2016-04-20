<?php

namespace App\Exceptions;

use App\Http\Controllers\ErrorController;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
//        if($this->isHttpException($e) && $e->getStatusCode() == '404') {
//
//            \Log::error($e);
//            $status = $e->getStatusCode();
//            $controller = new ErrorController();
//
//            if (view()->exists("errors.{$status}")) {
//                return response()->view("errors.{$status}", ['exception' => $e], $status);
//            } else {
//                return $this->convertExceptionToResponse($e);
//            }
//        }

        return parent::render($request, $e);
    }
}
