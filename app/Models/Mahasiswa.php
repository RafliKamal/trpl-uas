<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $fillable = [
    'userId',
    'nim',
    'nama',
    'email',
    'thnAngkatan',
    'status'
];
    protected $table = 'mahasiswas';
    protected $primaryKey = 'nim'; // Set primary key to nim
    public $incrementing = false; // Disable auto-incrementing since nim is a string
    protected $keyType = 'string'; // Set key type to string

    // Define any relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }
}
