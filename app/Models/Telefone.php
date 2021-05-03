<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Telefone
 *
 * @property int $id
 * @property int $user_id
 * @property string $telefone_fixo
 * @property string $telefone_celular
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone whereTelefoneCelular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone whereTelefoneFixo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Telefone whereUserId($value)
 * @mixin \Eloquent
 */
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
