<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class CustomException extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Ресурс не найден',
                ], 404);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Ошибка валидации',
                    'errors' => $exception->errors(),
                ], 422);
            }
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Неавторизован',
                ], 401);
            }

            return response()->json([
                'message' => $exception->getMessage(),
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
        }

        return parent::render($request, $exception);
    }
}
