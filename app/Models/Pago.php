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

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeDelMesActual($query)
    {
        return $query->where('fecha_pago', '>=', now()->startOfMonth())
            ->where('fecha_pago', '<=', now()->endOfMonth());
    }
}
