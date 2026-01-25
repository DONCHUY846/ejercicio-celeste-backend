<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Respuesta
 * 
 * Representa una respuesta a una pregunta en un evento.
 * 
 * @property int $id
 * @property int $id_pregunta
 * @property int $id_asistente
 * @property bool $respuesta
 */
class Respuesta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'respuestas';

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
        'id_pregunta',
        'id_asistente',
        'respuesta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'respuesta' => 'boolean',
    ];

    /**
     * Obtiene la pregunta asociada.
     */
    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta');
    }

    /**
     * Obtiene la asistencia asociada (quien respondió).
     */
    public function asistencia(): BelongsTo
    {
        return $this->belongsTo(Asistencia::class, 'id_asistente');
    }
}
