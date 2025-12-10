<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',

        'price',
    ];

    // Relation with Contact
    public function contact()
    {
        return $this->belongsTo(ContactPersons::class, 'contact_id');
    }
}
