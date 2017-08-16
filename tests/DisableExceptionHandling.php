<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Exception;

trait DisableExceptionHandling
{
    /**
     * Disable Exception Handling.
     */
    public function disableExceptionHandling()
    {
        app()->instance(ExceptionHandler::class, new class() extends Handler {
            public function __construct()
            {
            }

            public function report(Exception $e)
            {
            }

            public function render($request, Exception $exception)
            {
                throw $exception;
            }
        });
    }
}
