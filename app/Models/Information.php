<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = "informations";
    protected $fillable = [
        'title',
        'description',
        'type',
        'attachment',
        'visible_to',
        'status',
        'created_by',
          'information_date',
    ];

    /**
     * Relation: Information created by User
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope Active Informations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
