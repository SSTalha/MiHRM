<?php

namespace App\DTOs\LogsDTOs;

use App\DTOs\BaseDTOs;

class ErrorLogDTO extends BaseDTOs
{

    public string $requestLogId;
    public mixed $exception;
    public string $functionName;
    
    public function __construct(string $requestLogId, mixed $exception, string $functionName) {
        $this->request_log_id = $requestLogId;
        $this->line_number = $exception->getLine();
        $this->function_name = $functionName;
        $this->file_name = $exception->getFile();
        $this->error_message = $exception->getMessage();
    }

}