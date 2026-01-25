<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Gasto
 * 
 * Representa un gasto del condominio.
 * 
 * @property int $id
 * @property float $monto
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon $fecha
 */
class Gasto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gastos';

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
        'monto',
        'descripcion',
        'fecha',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date',
    ];
}
