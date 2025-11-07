<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'unique_id',
        'full_name',
        'email',
        'phone_number',
        'whatsapp_number',
        'alternative_number',
        'password',
        'role',
        'profile_pic',
        'status',
        'position',
        'permissions',
        'joining_date',
        'dob',
        'pan_card',
        'aadhaar_card',
        'last_qualification',
        'salary_slip',
        'previous_experience_letter',
        'previous_offer_letter',
        'bank_copy',
        'per_month_salary',
        'per_day_salary',
        'password_view',
    ];

    protected $casts = [
        'permissions' => 'array',
        'joining_date' => 'date',
        'dob' => 'date',
        'per_month_salary' => 'decimal:2',
        'per_day_salary' => 'decimal:2',
    ];

    // Relation: tasks assigned to me
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // Relation: tasks assigned by me
    public function assignedTasksByMe()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    // Relation: leaves
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
