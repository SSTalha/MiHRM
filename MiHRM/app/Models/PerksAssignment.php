<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerksAssignment extends Model
{
    protected $fillable = ['employee_id', 'perk_id'];

    public function perk()
    {
        return $this->belongsTo(Perk::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

