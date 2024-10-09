<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'reason',
        'status',       // 'pending', 'approved', 'rejected'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
