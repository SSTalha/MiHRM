<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class LeaveRequest extends Model
{
    use HasFactory,HasRoles;
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'reason',
        'status',       // 'pending', 'approved', 'rejected'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    // LeaveRequest.php
    public function user()
    {
        return $this->employee->user();
    }

}
