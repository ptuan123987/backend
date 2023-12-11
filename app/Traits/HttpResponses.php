<?php
namespace App\Traits;

trait HttpResponses {
    protected function success($data, $message = null , $code = 200) {
        return response()->json([
            "status"=> "Success",
            "message" => $message,
            "data"=> $data,
        ],$code);
    }

    protected function created($data, $message = null , $code = 201) {
        return response()->json([
            "status"=> "Created",
            "message" => $message,
            "data"=> $data,
        ],$code);
    }

    protected function error($data, $message = null , $code = 500 ) {
        return response()->json([
            "status"=> "Error has occurred",
            "message" => $message,
            "data"=> $data,
        ],$code);
    }
}
