<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Motivo
 * 
 * Representa un motivo de pago.
 * 
 * @property int $id
 * @property string $motivo
 */
class Motivo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'motivos';

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
        'motivo',
    ];

    /**
     * Obtiene los pagos asociados a este motivo.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_motivo');
    }
}
