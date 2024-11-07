<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'project_id', 'parent_task_id', 'completed'];

    // Define the relationship between Task and Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Define the relationship for nested tasks (subtasks)
    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    // Define the relationship to access the parent task
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }
}
