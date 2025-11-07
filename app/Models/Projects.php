<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
  
    protected $fillable =
        [
            'date',
            'project_name',
            'deadline',
            'priority',
            'status',
            'assigned_to',
            'assigned_by',
        ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
