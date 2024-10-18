<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'text', 'is_published', 'published_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
