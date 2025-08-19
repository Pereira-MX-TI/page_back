<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        // Verificar si es una solicitud a la API
        if (request()->is('api/*')) {
            // Registrar errores de la API
            Log::error('Error en la API', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
                'url' => request()->fullUrl(),
                'input' => request()->except($this->dontFlash),
            ]);
        }

        if ($exception instanceof CustomException) {
            Log::debug('Excepción controlada: '.$exception->getMessage(), [
                'code' => $exception->getCode(),
                'url' => request()->fullUrl(),
                'input' => request()->except($this->dontFlash),
            ]);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        // Manejo de excepciones controladas (ejemplo: CustomException)
        if ($exception instanceof CustomException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        // Manejo de otras excepciones no controladas
        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
