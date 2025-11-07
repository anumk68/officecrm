<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'task',
        'website',
        'deadline',
        'priority',
        'status',
        'assigned_to',
        'assigned_by',
        'completed_by',
        'completed_info',
    ];
    protected $casts = [
        'assigned_to' => 'array',
    ];

    public function assignedUser()
    {
        return User::whereIn('id', $this->assigned_to ?? [])->get();
    }
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
    // public function assignedUser()
    // {
    //     return $this->belongsTo(User::class, 'assigned_to');
    // }
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function remarks()
    {
        return $this->hasMany(Remark::class);
    }
}
