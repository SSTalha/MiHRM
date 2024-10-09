<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        
        'title',
        'description',
        'deadline',
        
    ];

    public function assignments()
    {
        return $this->hasMany(ProjectAssignment::class);
    }
}
