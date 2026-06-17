<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Materia;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
    public function index()
    {
        $programas = Programa::with('materias')->get();

        foreach ($programas as $programa) {
            $programa->total_materias = $programa->materias->count();
        }

        return view('programas.index', compact('programas'));
    }

    public function store(Request $request)
    {
        $programa = Programa::create($request->all());

        if ($request->has('materias')) {
            foreach ($request->input('materias') as $materiaId) {
                $programa->materias()->attach($materiaId);
            }
        }

        return redirect('/programas');
    }
}
