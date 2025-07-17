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
        'assigned_by'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function remarks()
    {
        return $this->hasMany(Remark::class);
    }

}
