<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Mensaje
 * 
 * Representa un mensaje entre residentes o departamentos.
 * 
 * @property int $id
 * @property int $remitente
 * @property int $destinatario
 * @property int $id_depaA
 * @property int $id_depaB
 * @property string $mensaje
 * @property \Illuminate\Support\Carbon $fecha
 */
class Mensaje extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mensajes';

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
        'remitente',
        'destinatario',
        'id_depaA',
        'id_depaB',
        'mensaje',
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
     * Obtiene la persona que envía el mensaje.
     */
    public function remitentePersona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'remitente');
    }

    /**
     * Obtiene la persona que recibe el mensaje.
     */
    public function destinatarioPersona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'destinatario');
    }

    /**
     * Obtiene el departamento A asociado al mensaje.
     */
    public function departamentoA(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depaA');
    }

    /**
     * Obtiene el departamento B asociado al mensaje.
     */
    public function departamentoB(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depaB');
    }
}
