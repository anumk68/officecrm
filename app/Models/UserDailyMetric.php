<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDailyMetric extends Model
{
    protected $fillable = [
        'user_id', 'date', 'tasks_completed', 'leads_created', 'sales_amount','hours_logged'
    ];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
