<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadImport extends Model
{
    protected $fillable = ['filename', 'original_name', 'total_rows', 'successful_rows', 'failed_rows', 'failed_details', 'uploaded_by'];
    protected $casts = ['failed_details' => 'array'];
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
