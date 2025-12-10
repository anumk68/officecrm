<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        "user_id",
        "role",
        "login_time",
        "logout_time", 
        "status",
    ];
    protected $dates = [
        'login_time',
        'logout_time',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
