<?php

namespace App\Exceptions;

use App\DTOs\ErrorLogDTO;
use App\Models\ErrorLogs;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function render($request, Throwable $exception)
    {
      $requestLogId = $request['request_log_id'];
      $errorLogDTO = (new ErrorLogDTO(
        $requestLogId,
        $exception,
        __FUNCTION__,
      ))->toArray();

      ErrorLogs::create($errorLogDTO);
      return response()->json(['error' => $exception->getMessage()], 500);
    }
}
