<?php

if (!function_exists('formatResponse'))
{
    /**
     * Format Response User Controller
     *
     * @param  bool  $error
     * @param  array $array
     * @return \Illuminate\Http\JsonResponse
     */
    function formatResponse(bool $error, array $array){
        return response()->json([
            "error" => $error,
            ($error == false ? "data" : "message") => $array
        ]);
    }
}
