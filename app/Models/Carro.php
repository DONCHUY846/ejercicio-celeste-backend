<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Carro
 * 
 * Representa un carro asociado a un departamento.
 * 
 * @property int $id
 * @property int $id_depa
 * @property string $placa
 * @property string|null $marca
 * @property string|null $modelo
 * @property string|null $color
 */
class Carro extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carros';

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
        'id_depa',
        'placa',
        'marca',
        'modelo',
        'color',
    ];

    /**
     * Obtiene el departamento al que pertenece el carro.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depa');
    }
}
