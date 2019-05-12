<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Exceptions\InvalidParameterException; 
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Exceptions\DatabaseTransactionErrorException;

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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {

            $preException = $exception->getPrevious();
        
            if ($preException instanceof TokenInvalidException) {
                return response()->json(['error' => true,'message' => ["token"=>["Invalid Token"]]],400);
            }
            elseif ($preException instanceof TokenExpiredException) {
                return response()->json(['error' => true, 'message' => ["token"=>["Token is Expired"]]],400);
            }
            elseif ($preException instanceof TokenBlacklistedException) {
                return response()->json(['error' => true, 'message' => ["token"=>['Token is Blacklist']]],400);
            }
            
        
           if ($exception->getMessage() === 'Token not provided') {
               return response()->json(['error' => true, 'message' => ["token"=>['Token not provided']]]);
           }
           elseif ($exception->getMessage() === 'User not found'){
                return response()->json(['error' => true, 'message' => ["user"=>['User not found']]]);
           }
        }
        elseif($exception instanceof ModelDontHaveRelation){
            return ModelDontHaveRelation::render($exception->getMessage());
        }
        elseif($exception instanceof DatabaseTransactionErrorException){
            return DatabaseTransactionErrorException::render($exception->getMessage());
        }
        elseif($exception instanceof InvalidParameterException){
            return InvalidParameterException::render($exception->getMessage());
        }
        elseif($exception instanceof ModelNotFoundException){
            return ModelNotFoundException::render($exception->getMessage());
        }
        elseif($exception instanceof MethodNotAllowedHttpException){
            return response()->json(['error' => true, 'message' => ["route"=>'Method not allowed']]);
        }
        return parent::render($request, $exception);
    }
}
