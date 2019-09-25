<?php

namespace App\Exceptions;

use Exception;

class ModelDontHaveRelation extends Exception
{
    public static function render(string $error){
        $error = json_decode($error);
        $from = $error[0];
        $to = $error[1];
        return response()->json([
            "error" => true,
            "message" => [
                "Relationship" => "Model $from dont have realtionship with model $to" ]
        ]);
    }
}
