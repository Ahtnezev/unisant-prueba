<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoApiController extends Controller
{
    public function show($matricula)
    {
        $alumno = Alumno::where('matricula', $matricula)->first();
        return response()->json($alumno);
    }

    public function pagos($matricula)
    {
        $alumno = Alumno::where('matricula', $matricula)->first();
        $pagos = \App\Models\Pago::where('matricula', $matricula)->get();

        return response()->json([
            'alumno' => $alumno,
            'pagos' => $pagos,
        ]);
    }
}
