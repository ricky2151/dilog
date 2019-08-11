<?php

namespace App\Exceptions;

use Exception;

class InvalidChangeStatusPoException extends Exception
{
    public static function render(string $error){
        $error = explode(" ", $error);
        $action = $error[0];
        $condition = implode(" ", array_splice($error,1));
        return response()->json([
            "error" => true,
            "message" => [
                "purchase order"=>[
                    "Can't $action PO because status is $condition"
                ]
            ]
        ]);
    }
}
