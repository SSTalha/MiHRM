<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'deadline',
        'status',       // 'pending' or 'completed'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
