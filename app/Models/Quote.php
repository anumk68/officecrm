<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $table = 'quotes';

    protected $casts = [
        'billing_address'  => 'array',
        'shipping_address' => 'array',
        'expired_at'       => 'datetime',
    ];

    protected $fillable = [
        'subject',
        'description',
        'billing_address',
        'shipping_address',
        'discount_percent',
        'discount_amount',
        'tax_percent',
        'tax_amount',
        'adjustment_amount',
        'sub_total',
        'grand_total',
        'expired_at',
        'user_id',
        'person_id',
        'lead_id',
    ];

    public function items()
    {
        return $this->hasMany(QuoteItem::class, 'quote_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function person()
    {
        return $this->belongsTo(ContactPersons::class, 'person_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

}
