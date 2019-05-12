<?php

namespace App\Exceptions;

use Exception;

class DatabaseTransactionErrorException extends Exception
{
    public static function render(string $error){
        return response()->json([
            "error" => true,
            "message" => [
                $error => ["Transaction error on model $error"]]
        ]);
    }
}
