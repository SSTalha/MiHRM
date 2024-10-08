<?php

namespace App\Helpers;

class Helpers
{
    public static function result($message,$statusCode, $data=[]){
        return response()->json([
            'message' => $message,
            'status_code' => $statusCode,
            'data' => $data
        ],$statusCode);
    }

    public static function respondWithToken($token)
    {
        return  ['token'=>$token];
            
    }
}