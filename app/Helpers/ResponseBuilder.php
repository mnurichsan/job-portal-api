<?php

namespace App\Helpers;

class ResponseBuilder {

    public static function success($message = "",$data = [],$statusCode = 0)
    {
        return response()->json([
            'success'=>true, 
            'message'=>$message, 
            'data'=>$data
        ],$statusCode);
    }

    public static function error($message = "",$data = [],$statusCode = 0)
    {
        return response()->json([
            'success'=>false, 
            'message'=>$message,
            'data' => $data
        ],$statusCode);
    }

}