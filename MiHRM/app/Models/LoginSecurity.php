<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginSecurity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'google2fa_enable', 'google2fa_secret', 'two_fa_password'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
