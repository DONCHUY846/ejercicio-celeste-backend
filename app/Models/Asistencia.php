<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Asistencia
 * 
 * Representa la asistencia de una persona a un evento.
 * 
 * @property int $id
 * @property int $id_persona
 * @property int $id_evento
 * @property string $hora
 */
class Asistencia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asistencia';

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
        'id_evento',
        'hora',
    ];

    /**
     * Obtiene la persona que asistió.
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    /**
     * Obtiene el evento al que asistió.
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    /**
     * Obtiene las respuestas dadas en esta asistencia.
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'id_asistente');
    }
}
