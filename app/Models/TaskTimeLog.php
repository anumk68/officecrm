<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTimeLog extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'start_time',
        'end_time',
        'duration_seconds',
        'start_remaining_seconds',
        'is_overtime',
        'overtime_seconds',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_overtime' => 'boolean',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
