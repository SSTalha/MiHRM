<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    protected $fillable = ['project_id', 'employee_id', 'status'];

    // Relationship to project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relationship to employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
