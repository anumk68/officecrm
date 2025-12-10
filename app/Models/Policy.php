<?php
// app/Models/Policy.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'file_path',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // helper to get public URL if stored in public disk
    public function fileUrl()
    {
        return $this->file_path ? asset('storage/app/public/policies/' . basename($this->file_path)) : null;
    }

    // helper filename
    public function filename()
    {
        return $this->file_path ? basename($this->file_path) : null;
    }
}
