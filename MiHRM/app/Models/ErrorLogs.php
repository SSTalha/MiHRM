<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLogs extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function requestLog()
    {
        return $this->belongsTo(RequestLogs::class);
    }
}
