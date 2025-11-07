<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'title',
        'description',
        'participant_id',
        'schedule_from',
        'schedule_to',
        'location',
        'activity_type',
    ];

    /**
     * Get the lead that this activity belongs to.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the participant assigned to this activity.
     */
    public function participant()
    {
        return $this->belongsTo(ContactPersons::class);
    }
}
