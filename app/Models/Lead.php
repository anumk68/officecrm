<?php
// app/Models/Lead.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'contact_person_id',
        'lead_product_id',
        'lead_title',
        'status',
        'lead_value',
        'source',
        'notes'
    ];

    public function contactPerson()
    {
        return $this->belongsTo(ContactPersons::class);
    }

    public function leadProduct()
    {
        return $this->belongsTo(LeadProduct::class);
    }
}
