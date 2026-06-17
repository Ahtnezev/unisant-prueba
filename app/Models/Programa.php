<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';
    protected $fillable = ['nombre', 'objetivo', 'inscripcion', 'precio_materia', 'precio_materia_reprobada', 'plazos_colegiatura', 'minima', 'limite_pago', 'modalidad_id', 'rvoe_id', 'tags'];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'programa_materia');
    }

}
