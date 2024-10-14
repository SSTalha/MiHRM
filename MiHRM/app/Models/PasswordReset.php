<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';
    protected $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['email', 'token', 'created_at'];

    public static function createToken($email, $token)
    {
        return self::updateOrCreate(
            ['email' => $email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
    }
}
