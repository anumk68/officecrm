<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'whatsapp_number',
        'position',
        'dob',
        'joining_date',
        'profile_pic',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
