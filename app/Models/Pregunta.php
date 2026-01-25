<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Pregunta
 * 
 * Representa una pregunta asociada a un evento.
 * 
 * @property int $id
 * @property string $pregunta
 * @property int $id_evento
 */
class Pregunta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preguntas';

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
        'pregunta',
        'id_evento',
    ];

    /**
     * Obtiene el evento asociado a la pregunta.
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    /**
     * Obtiene las respuestas a esta pregunta.
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'id_pregunta');
    }
}
