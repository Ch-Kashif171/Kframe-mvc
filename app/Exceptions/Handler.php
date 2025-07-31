<?php

namespace App\Exceptions;

use Core\Exception\ExceptionDispatcher;
use Core\Exception\Handlers\AuthException;
use Core\Exception\Handlers\NotFoundException;
use Core\Exception\Handlers\ValidationException;
use Exception;
use Throwable;

class Handler extends ExceptionDispatcher
{

    /**
     * @param Throwable $e
     * @return void
     * @throws Exception
     */
    public function handle(Throwable $e): void
    {
        /**
         * You can uncomment these if you want to customize the exception
         */
//        if ($e instanceof NotFoundException) {
//            response()->json(['NotFoundException' => $e->getMessage()], 404);
//        } elseif ($e instanceof ValidationException) {
//            response()->json(['NotFoundException' => $e->getErrors()], 422);
//        } elseif ($e instanceof AuthException) {
//            response()->json('Unauthenticated.', 401);
//        } elseif ($e instanceof Exception) {
//            response()->json(['Exception' => 'Something went wrong.'], 500);
//        }

        throw new \Exception($e->getMessage());
    }

}
