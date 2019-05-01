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


    /**
     * Function store image
     *
     * @param  $image
     * @param  string $pathStorage
     * @param  string $name
     * @return string $path
     */
    function storeImage($image, string $pathStorage, string $name){
        $path = $image->storeAs($pathStorage, $name);
        return $path;
    }

    /**
     * Function delete image
     *
     * @param  string $name
     */
    function deleteImage(string $name){
        Storage::delete($name);
    }
}
