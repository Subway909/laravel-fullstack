<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    use HasFactory;

    protected $fillable = [
        'telefone_fixo', 'telefone_celular'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
