<?php

namespace App\DTOs\AuthDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery\Generator\StringManipulation\Pass\Pass;

class RegisterDTO extends BaseDTOs
{
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public string $remember_token;

    /**
     * Construct the DTO with the input request.
     */
    public function __construct(mixed $request)
    {
        $this->name = $request['name'];
        $this->email =$request['email'];
        $this->password=Hash::make('password');
        $this->role = $request['role'];
        $this->remember_token = Str::random(40);
    }
}