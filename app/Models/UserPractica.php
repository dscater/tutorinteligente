<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPractica extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "practica_id",
        "codigo",
        "correcto",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function practica()
    {
        return $this->belongsTo(Practica::class, 'practica_id');
    }
}
