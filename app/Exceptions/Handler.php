<?php

namespace App\Exceptions;

use BadMethodCallException;
use Dotenv\Exception\ValidationException;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use UnexpectedValueException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
     
        // return $exception;
        // dd($exception);

        if ($_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'PUT' && $_SERVER['REQUEST_METHOD'] != 'DELETE' ) {
            return $this->respuesta('El api rest no soporta este metodo: ' . $_SERVER['REQUEST_METHOD'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        else if ($exception instanceof UnexpectedValueException) {
            return $this->respuesta("algo paso aqui mano. -> ". $exception->getMessage(), Response::HTTP_CONFLICT);
        } 
        else if ($exception instanceof ValidationException) {
            return $this->respuesta("No paso la validación. -> ". $exception->getMessage(), Response::HTTP_CONFLICT);
        } 
        else if ($exception instanceof ModelNotFoundException) {
            return $this->respuesta("El modelo no existe. -> ". $exception->getMessage(), Response::HTTP_CONFLICT);
        } 
        else if ($exception instanceof BadMethodCallException) {
            return $this->respuesta("El metodo solicitado esta indefinido o no existe. -> ". $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
        else if ($exception instanceof QueryException) {
            return $this->respuesta("A enviado campos vacios o dato duplicado. -> " .  $exception->getMessage() , Response::HTTP_CONFLICT);
        }
        
        else if ($exception instanceof ErrorException) {
            return $this->respuesta("Hay datos indefinido -> ". $exception->getMessage(), Response::HTTP_CONFLICT);
        }
        else if ($exception instanceof RouteNotFoundException) {
            return $this->respuesta("Acceso denegado -> ". $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
        else if ($exception instanceof NotFoundHttpException) {
            return $this->respuesta("La ruta solicitada no existe. -> ". $exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
        else if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->respuesta("El método utilizado no compatible con esta ruta. -> ". $exception->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $exception);
    }




    public function respuesta($message, $httpResponse){
        return response()->json([
            "message" => $message,
            "status" => $httpResponse
        ], $httpResponse);
    }

}
