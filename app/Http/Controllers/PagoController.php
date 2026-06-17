<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Alumno;
use App\Models\Adeudo;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function create()
    {
        $alumnos = Alumno::select(['nombre_completo', 'curp', 'matricula'])
            ->activos()
            ->where('nombre_completo', '!=', null)
            ->where('curp', '!=', null)
            ->get()
            ->unique('curp')
            ->values();

        return view('pagos.create', compact('alumnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required|string|max:255|exists:alumnos,matricula',
            'concepto' => 'required',
            'monto' => 'numeric|min:0|max:999999.99',
            'fecha_pago' => 'required|date|before_or_equal:today|after_or_equal:' . now()->subMonth()->toDateString(),
            'metodo' => 'in:efectivo,transferencia,tarjeta',
            'sede_id' => 'required|numeric|min:0'
        ]);

        //  el request se valida que exista la matricula
        $alumno = Alumno::firstWhere('matricula', $request->input('matricula'));
        if ($alumno) {
            return redirect()->route('pagos.create')->with('fail', 'Alumno no encontrado.');
        }
        $monto = (float) $request->monto;

        $pago = Pago::create([
            'matricula' => $request->input('matricula'),
            'concepto' => $request->input('concepto', 'Pago general'),
            'monto' => $monto,
            'fecha_pago' => $request->input('fecha_pago', now()),
            'metodo' => $request->input('metodo', 'efectivo'),
            'sede_id' => $request->input('sede_id'),
            'estado' => 'activo', // crear un enum para manejar estados o en el mismo modelo
        ]);

        $comision = round($request->input('monto') * 0.05, 2);
        $pago->nota = 'Comisión: ' . $comision;
        $pago->save();

        return redirect('/pagos/create')->with('success', 'Pago registrado');
    }

    public function index()
    {
        $pagos = Pago::with(['alumno'])
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('pagos.index', compact('pagos'));
    }
}
