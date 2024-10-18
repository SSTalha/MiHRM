<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'check_in_time',
        'check_out_time', 
        'working_hours', 
        'date',
        'status',          // 'present', 'absent', 'onleave'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
}
