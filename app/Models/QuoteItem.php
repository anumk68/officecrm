<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    protected $table = 'quote_items';

    protected $fillable = [
        'sku',
        'name',
        'quantity',
        'price',
        'coupon_code',
        'discount_percent',
        'discount_amount',
        'tax_percent',
        'tax_amount',
        'total',
        'project_id',
        'quote_id',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function project()
    {
        return $this->belongsTo(LeadProduct::class);
    }
}
