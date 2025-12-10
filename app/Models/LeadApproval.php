<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadApproval extends Model
{
    protected $fillable = [
        'lead_id',
        'approved_by',
        'status',
        'notes',
        'client_requirement',
        'budget_confirmation',
        'authenticity',
        'document',
        'approved_at',
    ];
    protected $casts = [
        'approved_at' => 'datetime',
    ];
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
