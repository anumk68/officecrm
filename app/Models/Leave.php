<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //
    protected $fillable = [
        'user_id',
        'role',
        'date_from',
        'date_to',
        'leave_type',
        'reason',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
