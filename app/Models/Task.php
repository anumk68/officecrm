<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'data',
        'task',
        'website',
        'deadline',
        'priority',
        'status',
        'assigned_to'
    ];
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
