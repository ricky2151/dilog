<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    public static function render(string $error){
        return response()->json([
            "error" => true,
            "message" => [
                $error => ["not found"]]
        ]);
    }
}
