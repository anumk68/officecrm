<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'subject', 'message',
        'attachment', 'status', 'assigned_to', 'hr_response'
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }



}
