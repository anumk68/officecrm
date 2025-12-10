<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;

    protected $fillable = [

        // Company Information
        'company_logo',
        'company_name',
        'company_tagline',
        'company_address',

        // Employee Information
        'employee_id',
        'employee_name',
        'emp_code',
        'designation',
        'joining_date',
        'bank_account',

        // Month
        'month',

        // Salary
        'basic',
        'incentives',
        'overtime',

        // Deductions
        'unpaid_leave_amount',
        'late_coming',

        // Attendance
        'working_days',
        'on_duty',
        'unpaid_leave_days',

        // Personal
        'father_name',
        'address',
        'dob',
        'mobile',
        'email',

        // Salary Final
        'net_pay',

        // PDF file path
        'file_path',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'dob' => 'date',
    ];

    // Relationship: belongs to Employee (User model)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
