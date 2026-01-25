<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Control
 * 
 * Representa un control de acceso asociado a un departamento.
 * 
 * @property int $id
 * @property string $codigo
 * @property int $id_depa
 */
class Control extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'controles';

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
        'codigo',
        'id_depa',
    ];

    /**
     * Obtiene el departamento al que pertenece el control.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depa');
    }
}
