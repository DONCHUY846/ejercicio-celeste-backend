<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PersonaDepartamento
 * 
 * Representa la relación entre persona, departamento y rol (Tabla pivote).
 * 
 * @property int $id_persona
 * @property int $id_depa
 * @property int $id_rol
 * @property bool $residente
 * @property string|null $codigo
 */
class PersonaDepartamento extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'per_dep';

    /**
     * The primary key for the model.
     *
     * @var array
     */
    protected $primaryKey = ['id_persona', 'id_depa', 'id_rol'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        'id_persona',
        'id_depa',
        'id_rol',
        'residente',
        'codigo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'residente' => 'boolean',
    ];

    /**
     * Obtiene la persona asociada.
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    /**
     * Obtiene el departamento asociado.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depa');
    }

    /**
     * Obtiene el rol asociado.
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
