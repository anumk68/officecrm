<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table    = 'emails';
    protected $fillable = [
        'to',
        'cc',
        'bcc',
        'subject',
        'message',
        'is_draft',
    ];

    protected $casts = [
        'to'       => 'array',
        'cc'       => 'array',
        'bcc'      => 'array',
        'is_draft' => 'boolean',
    ];
}
