<?php

namespace App\Exceptions;

use Exception;

class InvalidParameterException extends Exception
{
    /**
     * Generating invalid parameter response(s)
     * 
     * @param  \string  $error
     * @return \Illuminate\Http\JsonResponse
     */
    public static function render(string $error){
        return response()->json([
            "error" => true,
            "message" => json_decode($error)    
        ], 422);
    }
}
