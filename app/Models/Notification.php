<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id',
        'role_targets',
        'module',
        'title',
        'message',
        'is_read',
        'read_by',
    ];

    protected $casts = [
        'role_targets' => 'array',
        'read_by' => 'array',
        'is_read' => 'boolean',
    ];
}
