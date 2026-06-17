<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Sede;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
        $validated = $request->validate([
            'matricula' => 'required|string|max:20|unique:alumnos,matricula',
            'nombre_completo' => 'required|string|max:255',
            'apat' => 'required|string|max:100',
            'amat' => 'required|string|max:100',
            'curp' => 'required|string|size:18|unique:alumnos,curp',
            'email' => 'required|email|max:255|unique:alumnos,email',
            'telefono' => 'required|string|max:15',
            'sede_id' => 'required|exists:sedes,id',
            'fecha_nacimiento' => 'nullable|date', //|before:today
            'programa_id' => 'nullable', //exists:programas,id
            'monto_inicial' => 'nullable|numeric|min:0|max:999999.99',
        ], [
            'matricula.unique' => 'Esta matrícula ya está registrada',
            'curp.unique' => 'Esta CURP ya está registrada',
            'email.unique' => 'Este email ya está registrado',
            'sede_id.exists' => 'La sede seleccionada no existe',
            'programa_id.exists' => 'El programa seleccionado no existe',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
        ]);

        try {
            DB::beginTransaction();

            $alumno = Alumno::create([
                'matricula' => $validated['matricula'],
                'nombre_completo' => $validated['nombre_completo'],
                'apat' => $validated['apat'] ?? null,
                'amat' => $validated['amat'] ?? null,
                'curp' => $validated['curp'] ?? null,
                'email' => $validated['email'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'sede_id' => $validated['sede_id'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'estado' => 'activo',
            ]);

            if (!empty($validated['programa_id'])) {
                $inscripcion = Inscripcion::create([
                    'alumno_id' => $alumno->id,
                    'programa_id' => $validated['programa_id'],
                    'sede_id' => $validated['sede_id'],
                    'estado' => 'activo',
                    'fecha_inscripcion' => now(),
                ]);
            }

            if (!empty($validated['monto_inicial']) && $validated['monto_inicial'] > 0) {
                Pago::create([
                    'matricula' => $alumno->matricula,
                    'concepto' => 'Inscripción inicial',
                    'monto' => $validated['monto_inicial'],
                    'fecha_pago' => now(),
                    'sede_id' => $validated['sede_id'],
                    'estado' => 'completed',
                    'metodo' => $request->input('tipo_pago', 'efectivo'),
                ]);
            }

            DB::commit();

            return redirect()->route('alumnos.index')
                ->with('success', 'Alumno creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el alumno: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('fail', 'Error al eliminar usuario, intente más tarde.');
        }
        $alumno->delete();

        return redirect()->route('alumnos.index');
    }
}
