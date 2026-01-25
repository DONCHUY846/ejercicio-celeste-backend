<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Pago
 * 
 * Representa un pago realizado por un departamento.
 * 
 * @property int $id
 * @property int $id_depa
 * @property float $monto
 * @property int $id_tipo
 * @property \Illuminate\Support\Carbon $fecha
 * @property int $id_motivo
 * @property string|null $descripcion
 * @property string|null $comprobante
 * @property bool $efectuado
 * @property int|null $id_reporte
 */
class Pago extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pagos';

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
        'id_depa',
        'monto',
        'id_tipo',
        'fecha',
        'id_motivo',
        'descripcion',
        'comprobante',
        'efectuado',
        'id_reporte',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date',
        'efectuado' => 'boolean',
    ];

    /**
     * Obtiene el departamento que realiza el pago.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depa');
    }

    /**
     * Obtiene el tipo de pago.
     */
    public function tipoPago(): BelongsTo
    {
        return $this->belongsTo(TipoPago::class, 'id_tipo');
    }

    /**
     * Obtiene el motivo del pago.
     */
    public function motivo(): BelongsTo
    {
        return $this->belongsTo(Motivo::class, 'id_motivo');
    }

    /**
     * Obtiene el reporte asociado al pago.
     */
    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class, 'id_reporte');
    }

    /**
     * Obtiene el registro de mantenimiento asociado al pago (si existe).
     */
    public function mantenimiento(): HasOne
    {
        return $this->hasOne(Mantenimiento::class, 'id_pago');
    }
}
