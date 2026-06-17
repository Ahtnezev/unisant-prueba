<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $fillable = [
        'matricula', 'concepto', 'monto', 'fecha_pago',
        'metodo', 'sede_id', 'conciliacion_id', 'estado', 'nota', 'chunk'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'matricula', 'matricula');
    }
}
