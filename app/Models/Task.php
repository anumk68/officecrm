<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'created_by',
        'title',
        'description',
        'deadline',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'assigned_to' => 'array',
    ];

    public function assignedUsers()
    {
        // Always decode safely
        $assigned = $this->assigned_to;

        // If null or empty
        if (empty($assigned)) {
            return collect(); // return empty collection
        }

        // If JSON string → convert to array
        if (is_string($assigned)) {
            $assigned = json_decode($assigned, true);
        }

        // If still not array → convert empty
        if (!is_array($assigned)) {
            return collect();
        }

        // Remove "Anyone"
        $assigned = array_filter($assigned, function ($v) {
            return $v !== "Anyone";
        });

        // If all removed
        if (empty($assigned)) {
            return collect();
        }

        // Finally get users
        return User::whereIn('id', $assigned)->get();
    }
    public function getAssignedUsersAttribute()
    {
        $assigned = $this->assigned_to;
        if (is_string($assigned)) {
            $assigned = json_decode($assigned, true);
        }

        if (!is_array($assigned) || empty($assigned)) {
            return collect();
        }

        // Remove "Anyone"
        $assigned = array_filter($assigned, fn($v) => $v !== "Anyone");

        if (empty($assigned)) {
            return collect();
        }

        return User::whereIn('id', $assigned)->get();
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
   public function project()
{
    return $this->belongsTo(Projects::class, 'project_id', 'id');
}

    public function remarks()
    {
        return $this->hasMany(Remark::class);
    }

    public function timeLogs()
{
    return $this->hasMany(TaskTimeLog::class, 'task_id');
}


 
public function assigned_users_relation()
{
    $assigned = $this->assigned_to;

    if (is_string($assigned)) {
        $assigned = json_decode($assigned, true);
    }

    if (!is_array($assigned) || empty($assigned)) {
        return collect(); // return empty collection (Not a query)
    }

    // Remove "Anyone"
    $assigned = array_filter($assigned, fn($v) => $v !== "Anyone");

    if (empty($assigned)) {
        return collect();
    }

    // Return a COLLECTION, not a relationship!
    return User::whereIn('id', $assigned)->get();
}



}
