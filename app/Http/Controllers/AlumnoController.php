<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Services\AlumnoService;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    protected AlumnoService $alumnoService;

    public function __construct(AlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }

    public function index(Request $request)
    {
        $data = $this->alumnoService->getAlumnosFiltrados($request);
        return view('alumnos.index', [
            'alumnos' => $data['alumnos'],
            'sedes' => $data['sedes'],
        ]);
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
            'fecha_nacimiento' => 'nullable|date|before:today',
            'programa_id' => 'nullable|exists:programas,id',
            'monto_inicial' => 'nullable|numeric|min:0|max:999999.99',
            'tipo_pago' => 'nullable|in:efectivo,tarjeta,transferencia',
        ], [
            'matricula.unique' => 'Esta matrícula ya está registrada',
            'curp.unique' => 'Esta CURP ya está registrada',
            'email.unique' => 'Este email ya está registrado',
            'sede_id.exists' => 'La sede seleccionada no existe',
            'programa_id.exists' => 'El programa seleccionado no existe',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'tipo_pago.in' => 'El tipo de pago no es válido',
        ]);

        $resultado = $this->alumnoService->crearAlumno($validated);

        if (!$resultado['success']) {
            return redirect()->back()
                ->withInput()
                ->with('fail', $resultado['message']);
        }

        return redirect()->route('alumnos.index')
            ->with('success', $resultado['message']);
    }

    public function destroy($id)
    {
        $resultado = $this->alumnoService->destroy($id);

        if (!$resultado['success']) {
            return redirect()->route('alumnos.index')
                ->with('fail', $resultado['motivo']);
        }

        return redirect()->route('alumnos.index')
            ->with('success', $resultado['motivo']);

    }
}
