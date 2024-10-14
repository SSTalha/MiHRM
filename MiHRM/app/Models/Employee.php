<?php

namespace App\Models;

use App\Models\Salary;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasRoles;
    protected $fillable = [
        'user_id',
        'position',
        'department_id',  // Foreign key to the Department table
        'pay', 
        'date_of_joining',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function salary()
    {
        return $this->hasMany(Salary::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function projectAssignments()
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
