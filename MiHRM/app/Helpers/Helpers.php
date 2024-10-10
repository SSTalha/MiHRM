<?php

namespace App\Helpers;
use Symfony\Component\HttpFoundation\Response;

class Helpers
{
    public static function result($message,$statusCode, $data=[]){

        $statusText = Response::$statusTexts[$statusCode];
        $statusCodeWithText ="{$statusCode}, {$statusText}";
        return response()->json([
            'message' => $message,
            'status_code' => $statusCodeWithText,
            'data' => $data
        ],$statusCode);
    }

    public static function respondWithToken($token)
    {
        return  ['token'=>$token];
            
    }
}