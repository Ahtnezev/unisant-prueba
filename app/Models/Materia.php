<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materias';
    protected $fillable = ['nombre', 'clave', 'alias', 'seriacion', 'creditos', 'planescolar_id', 'tipomateria_id'];

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'programa_materia');
    }

}
