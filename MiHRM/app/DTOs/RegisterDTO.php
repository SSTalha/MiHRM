<?php

namespace App\DTOs;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Mockery\Generator\StringManipulation\Pass\Pass;

class RegisterDTO extends BaseDTOs
{
    public string $name;
    public string $email;
    public string $password;
    public string $role;

    /**
     * Construct the DTO with the input request.
     */
    public function __construct(mixed $request)
    {
        $this->name = $request['name'];
        $this->email =$request['email'];
        $this->password=Hash::make('password');
        $this->role = $request['role'];
    }
}