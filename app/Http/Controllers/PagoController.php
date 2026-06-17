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
        $alumnos = Alumno::all();
        return view('pagos.create', compact('alumnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required',
            'monto' => 'required',
        ]);

        $alumno = Alumno::where('matricula', $request->input('matricula'))->first();
        $monto = (float) $request->input('monto');

        $pago = Pago::create([
            'matricula' => $request->input('matricula'),
            'concepto' => $request->input('concepto', 'Pago general'),
            'monto' => $request->input('monto'),
            'fecha_pago' => $request->input('fecha_pago', now()),
            'metodo' => $request->input('metodo', 'efectivo'),
            'sede_id' => $request->input('sede_id'),
            'estado' => 'activo',
        ]);

        $comision = $request->input('monto') * 0.05;
        $pago->nota = 'Comisión: ' . $comision;
        $pago->save();

        return redirect('/pagos/create')->with('success', 'Pago registrado');
    }

    public function index()
    {
        $pagos = Pago::orderBy('id', 'desc')->get();
        return view('pagos.index', compact('pagos'));
    }
}
