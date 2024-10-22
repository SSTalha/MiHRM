<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerksRequest extends Model
{
    protected $fillable = ['employee_id', 'requested_perks', 'status', 'total_allowance'];

    protected $casts = [
        'requested_perks' => 'array', // JSON to array
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

