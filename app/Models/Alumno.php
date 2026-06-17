<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';
    protected $fillable = [
        'matricula', 'nombre_completo', 'apat', 'amat', 'curp',
        'email', 'telefono', 'sede_id', 'organizacion_id',
        'fecha_nacimiento', 'fecha_inscripcion', 'estado', 'tags'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'alumno_id');
    }
}
