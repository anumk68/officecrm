<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remark extends Model
{
    use SoftDeletes;

    protected $fillable = ['task_id', 'user_id', 'text']; // Add 'user_id' to fillable, as you're setting it.

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Add a relationship to the User model
    }
}
