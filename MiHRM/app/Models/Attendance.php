<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'check_in_time',
        'check_out_time',  // Nullable field
        'date',
        'status',          // 'present', 'absent', 'onleave'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
