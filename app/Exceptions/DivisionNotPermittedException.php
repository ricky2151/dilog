<?php

namespace App\Exceptions;

use Exception;

class DivisionNotPermittedException extends Exception
{
    public static function render(){
        return response()->json([
            "error" => true,
            "message" => [
                "This Division can make Material Request"]
        ]);
    }
}
