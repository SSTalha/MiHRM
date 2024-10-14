<?php

namespace App\DTOs\AuthDTOs;

use App\DTOs\BaseDTOs;

class PasswordDTO extends BaseDTOs
{
    public $email;
    public $password;
    public $token;

    public function __construct(array $request) {
        $this->email =  $request['email'];
        $this->token = $request['token'];
        $this->password = $request['password'];
    }

}