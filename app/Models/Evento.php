<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Evento
 * 
 * Representa un evento en el condominio.
 * 
 * @property int $id
 * @property \Illuminate\Support\Carbon $fecha
 * @property string $descripcion
 */
class Evento extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eventos';

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
        'fecha',
        'descripcion',
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
     * Obtiene las preguntas asociadas al evento.
     */
    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'id_evento');
    }

    /**
     * Obtiene las asistencias al evento.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_evento');
    }
}
