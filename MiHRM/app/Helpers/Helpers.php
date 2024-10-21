<?php

namespace App\Helpers;
use App\DTOs\LogsDTOs\ErrorLogDTO;
use App\Models\ErrorLogs;
use Symfony\Component\HttpFoundation\Response;

class Helpers
{
    /**
     * Summary of result
     * @param string $message
     * @param int $statusCode
     * @param mixed $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public static function result(string $message, int $statusCode, mixed $data = null){

        return response()->json([
            'message' => $message,
            'data' => $data
        ],$statusCode);
    }

    /**
     * Summary of error
     * @param mixed $request
     * @param string $message
     * @param \Throwable $exception
     * @param int $statusCode
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public static function error($request, string $message , \Throwable $exception , int $statusCode = null){
        $requestLogId = $request['request_log_id'];

        $errorLogDTO = new ErrorLogDTO(
            $requestLogId,
            $exception,
            __FUNCTION__
        );

        ErrorLogs::create($errorLogDTO->toArray());
        return response()->json([
            'message' => $message,
            'error' => [
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'message' => $exception->getMessage(),
            ]
        ], $statusCode);

    }

    public static function respondWithToken($token)
    {
        return  ['token'=>$token];
    }
}