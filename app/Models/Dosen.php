<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;
    protected $fillable = [
    'userId',
    'nidn',
    'nama',
    'email',
    'status'
];
    protected $table = 'dosens';
    protected $primaryKey = 'nidn'; // Set primary key to nidn
    public $incrementing = false; // Disable auto-incrementing since nidn is a string
    protected $keyType = 'string'; // Set key type to string

    // Define any relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }
}
