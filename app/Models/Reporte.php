<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Reporte
 * 
 * Representa un reporte generado por un usuario.
 * 
 * @property int $id
 * @property int $id_usuario
 * @property string $reporte
 * @property \Illuminate\Support\Carbon $fecha
 */
class Reporte extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reportes';

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
        'id_usuario',
        'reporte',
        'fecha',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Obtiene el usuario que generó el reporte.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Obtiene los pagos asociados al reporte.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_reporte');
    }
}
