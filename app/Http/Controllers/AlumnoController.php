<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Sede;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query();
        $query->where('estado', 'activo')
            ->whereNotNull('nombre_completo')
            ->where('nombre_completo', '!=', '');

        if ($request->has('buscar') && $request->filled('buscar')) {
            $buscar = trim($request->input('buscar'));
            $query->where('nombre_completo', 'LIKE', '%' . $buscar . '%');
            $query->orWhere('matricula', 'LIKE', '%' . $buscar . '%');
        }

        if ($request->has('sede_id') && $request->filled('sede_id')) {
            $query->where('sede_id', $request->input('sede_id'));
        }

        $alumnos = $query->get()->unique('curp')->values();

        $page = request()->input('page', 1);
        $perPage = 5;
        $alumnos = new LengthAwarePaginator(
            $alumnos->forPage($page, $perPage),
            $alumnos->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $sedes = Sede::activas()->get();

        return view('alumnos.index', compact('alumnos', 'sedes'));
    }

    public function create()
    {
        $sedes = Sede::activas()->get();
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
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('fail', 'Error al eliminar usuario, intente más tarde.');
        }
        $alumno->delete();

        // podemos avisar que si se elimino correctamente...
        return redirect()->route('alumnos.index');
    }
}
