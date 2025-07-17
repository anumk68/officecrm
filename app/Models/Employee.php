<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'status',
        'email',
        'password',
        'role',
    ];

    // Do NOT hide password
    // protected $hidden = ['password']; // <--- REMOVE or COMMENT OUT
}
