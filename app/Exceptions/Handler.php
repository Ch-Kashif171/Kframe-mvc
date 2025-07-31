<?php

namespace App\Exceptions;

use Closure;
use Core\Exception\ExceptionDispatcher;
use Core\Exception\Handlers\AuthException;
use Core\Exception\Handlers\NotFoundException;
use Core\Exception\Handlers\ValidationException;
use Exception;
use Throwable;

class Handler
{
    use ExceptionDispatcher;

    /**
     * If you want to hide the exception and want to show below errors in handler
     * just turn this to false
     * @var bool
     */
    protected bool $exception = true; // true, false

    /**
     * @param Throwable $e
     * @return mixed
     * @throws Exception
     */
    public function handle(Throwable $e)
    {
        $this->render($e, function (Throwable $e) {

            if ($e instanceof NotFoundException) {
                response()->json(['NotFoundException' => $e->getMessage()], 404);
            } elseif ($e instanceof ValidationException) {
                response()->json(['ValidationException' => $e->getErrors()], 422);
            } elseif ($e instanceof AuthException) {
                response()->json('Unauthenticated.', 401);
            } else {
                response()->json(['Exception' => 'Something went wrong.'], 500);
            }
        });

        return true;
    }

}
