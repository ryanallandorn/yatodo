<?php

// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    // Define the relationship with tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
