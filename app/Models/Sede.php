<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedes';
    protected $fillable = ['nombre', 'clave', 'direccion', 'meta_alumnos', 'activa'];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'sede_id');
    }
}
