<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'pay',
        'status',        // 'paid' or 'unpaid'
        'paid_date',     // Date when salary is paid
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
