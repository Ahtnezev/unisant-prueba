<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Sede;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query();
        $alumnos = $query->get();

        if ($request->has('buscar')) {
            $buscar = $request->input('buscar');
            $query->whereRaw("nombre_completo LIKE '%" . $buscar . "%'");
        }

        if ($request->has('sede_id')) {
            $alumnos = $alumnos->where('sede_id', $request->input('sede_id'));
        }

        $sedes = Sede::all();

        return view('alumnos.index', compact('alumnos', 'sedes'));
    }

    public function create()
    {
        $sedes = Sede::all();
        return view('alumnos.create', compact('sedes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'matricula' => 'required',
            'sede_id' => 'required',
        ]);

        $alumno = Alumno::create($request->only([
            'matricula', 'nombre_completo', 'apat', 'amat',
            'curp', 'email', 'telefono', 'sede_id', 'fecha_nacimiento'
        ]));

        if ($request->has('programa_id')) {
            Inscripcion::create([
                'alumno_id' => $alumno->id,
                'programa_id' => $request->input('programa_id'),
                'sede_id' => $request->input('sede_id'),
                'estado' => 'activo',
                'fecha_inscripcion' => now(),
            ]);
        }

        if ($request->has('monto_inicial')) {
            Pago::create([
                'matricula' => $alumno->matricula,
                'concepto' => 'Inscripción',
                'monto' => $request->input('monto_inicial'),
                'fecha_pago' => now(),
                'sede_id' => $request->input('sede_id'),
                'estado' => 'activo',
            ]);
        }

        return redirect('/alumnos')->with('success', 'Alumno creado');
    }

    public function destroy($id)
    {
        $alumno = Alumno::find($id);
        $alumno->delete();

        return redirect('/alumnos');
    }
}
