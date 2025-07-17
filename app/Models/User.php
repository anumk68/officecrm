<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'profile_pic'
    ];

    // If you later want to check what tasks this user has:
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function assignedTasksByMe()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

}







// public function employee()
// {
//     return $this->belongsTo(Employee::class, 'employee_id');
// }