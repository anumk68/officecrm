<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendaceRemark extends Model
{
    //
    protected $fillable = [
        'user_id',
        'date',
        'description',
        'added_by'
    ];

    protected $casts = [
        'date' => 'date'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
