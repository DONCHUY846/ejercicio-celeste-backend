<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Persona
 * 
 * Representa a una persona en el sistema.
 * 
 * @property int $id
 * @property string $nombre
 * @property string $apellido_p
 * @property string|null $apellido_m
 * @property float $celular
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Persona extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'celular',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'celular' => 'decimal:0',
        'activo' => 'boolean',
    ];

    /**
     * Obtiene el usuario asociado a la persona.
     */
    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id_persona');
    }

    /**
     * Obtiene los departamentos asociados a la persona.
     */
    public function departamentos(): BelongsToMany
    {
        return $this->belongsToMany(Departamento::class, 'per_dep', 'id_persona', 'id_depa')
                    ->withPivot('id_rol', 'residente', 'codigo');
    }

    /**
     * Obtiene las asistencias de la persona.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_persona');
    }

    /**
     * Obtiene los mensajes enviados por la persona.
     */
    public function mensajesEnviados(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'remitente');
    }

    /**
     * Obtiene los mensajes recibidos por la persona.
     */
    public function mensajesRecibidos(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'destinatario');
    }
}
