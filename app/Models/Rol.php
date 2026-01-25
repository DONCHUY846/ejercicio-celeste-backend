<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Rol
 * 
 * Representa un rol que puede tener una persona en un departamento.
 * 
 * @property int $id
 * @property string $rol
 */
class Rol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
        'rol',
    ];

    /**
     * Obtiene las relaciones persona-departamento que tienen este rol.
     */
    public function personaDepartamentos(): HasMany
    {
        return $this->hasMany(PersonaDepartamento::class, 'id_rol');
    }
}
