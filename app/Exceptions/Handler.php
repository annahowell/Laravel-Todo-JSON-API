<?php

namespace App\Exceptions;

use CloudCreativity\LaravelJsonApi\Exceptions\HandlesErrors;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class Handler extends ExceptionHandler
{
    use HandlesErrors;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        JsonApiException::class,
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

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }


    public function render($request, Throwable $exception)
    {
        if ($this->isJsonApi($request, $exception)) {
            return $this->renderJsonApi($request, $exception);
        }

        return parent::render($request, $exception);
    }


    protected function prepareException(Throwable $exception)
    {
        if ($exception instanceof JsonApiException) {
            return $this->prepareJsonApiException($exception);
        }

        return parent::prepareException($exception);
    }
}
