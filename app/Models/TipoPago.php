<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TipoPago
 * 
 * Representa un tipo de pago.
 * 
 * @property int $id
 * @property string $tipo
 */
class TipoPago extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipos_pago';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo',
    ];

    /**
     * Obtiene los pagos de este tipo.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_tipo');
    }
}
