<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Endereco
 *
 * @property int $id
 * @property int $user_id
 * @property string $logradouro
 * @property string $numero
 * @property string $bairro
 * @property string $cep
 * @property string|null $complemento
 * @property string $cidade
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco query()
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereCidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereComplemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereLogradouro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Endereco whereUserId($value)
 * @mixin \Eloquent
 */
class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [
        'logradouro', 'numero', 'bairro', 'cep', 'complemento', 'cidade'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
