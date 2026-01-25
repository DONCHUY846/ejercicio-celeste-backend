<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Departamento
 * 
 * Representa un departamento en el condominio.
 * 
 * @property int $id
 * @property string $departamento
 * @property bool $moroso
 * @property string $codigo
 */
class Departamento extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departamentos';

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
        'departamento',
        'moroso',
        'codigo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'moroso' => 'boolean',
    ];

    /**
     * Obtiene las personas asociadas al departamento.
     */
    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class, 'per_dep', 'id_depa', 'id_persona')
                    ->withPivot('id_rol', 'residente', 'codigo');
    }

    /**
     * Obtiene los carros asociados al departamento.
     */
    public function carros(): HasMany
    {
        return $this->hasMany(Carro::class, 'id_depa');
    }

    /**
     * Obtiene los controles asociados al departamento.
     */
    public function controles(): HasMany
    {
        return $this->hasMany(Control::class, 'id_depa');
    }

    /**
     * Obtiene los pagos asociados al departamento.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_depa');
    }

    /**
     * Obtiene los registros de mantenimiento asociados al departamento.
     */
    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class, 'id_depa');
    }

    /**
     * Obtiene los mensajes donde este departamento es el A.
     */
    public function mensajesA(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'id_depaA');
    }

    /**
     * Obtiene los mensajes donde este departamento es el B.
     */
    public function mensajesB(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'id_depaB');
    }
}
