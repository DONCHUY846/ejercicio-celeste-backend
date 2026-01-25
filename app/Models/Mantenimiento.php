<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Mantenimiento
 * 
 * Representa el registro de mantenimiento mensual de un departamento.
 * 
 * @property int $id
 * @property int $mes
 * @property int $anio
 * @property int $id_depa
 * @property bool $completado
 * @property float $monto
 * @property int|null $id_pago
 */
class Mantenimiento extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mantenimiento';

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
        'mes',
        'anio',
        'id_depa',
        'completado',
        'monto',
        'id_pago',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completado' => 'boolean',
        'monto' => 'decimal:2',
    ];

    /**
     * Obtiene el departamento asociado.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depa');
    }

    /**
     * Obtiene el pago asociado.
     */
    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'id_pago');
    }
}
