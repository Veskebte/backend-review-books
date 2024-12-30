<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'judul',
        'penulis',
        'genre',
        'deskripsi',
        'foto',
    ];

    public function getFotoAttribute($value) {
        return $value ? asset('storage/'.$value) : null;
    }
}
