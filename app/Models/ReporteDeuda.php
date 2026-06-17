<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteDeuda extends Model
{
    protected $table = 'reporte_deudas';
    protected $fillable = ['nombre', 'filtros', 'resultado', 'estado'];

}
