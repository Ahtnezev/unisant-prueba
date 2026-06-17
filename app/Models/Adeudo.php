<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adeudo extends Model
{
    protected $table = 'adeudos';
    protected $fillable = [
        'alumno_id', 'nombre', 'monto', 'plazos', 'desde', 'dias', 'divisa_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

}
