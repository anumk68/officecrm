<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // CRM Friendly ID
        'lead_id',

        // Facebook / Instagram Lead Fields
        'fb_lead_unique_id',
        'created_time',
        'ad_id',
        'ad_name',
        // 'adset_id',
        'adset_name',
        // 'campaign_id',
        // 'campaign_name',
        // 'form_id',
        // 'form_name',
        // 'is_organic',
        'platform',

        // Lead Details
        'email',
        'full_name',
        'phone_number',
        'city',

        // CRM Fields
        'lead_source_id',
        'status',
        'color',
        'notes',
        'assigned_to',
        'created_by',

        // Extra fields JSON
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    public static function booted()
    {
        static::creating(function ($lead) {
            if (empty($lead->lead_id)) {
                $lead->lead_id =
                    'LD-' . now()->format('Ymd') . '-' .
  
                    str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function approvable()
    {
        return $this->hasOne(LeadApproval::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
