<?php

namespace App\Http\Controllers;

use App\Models\Practica;
use App\Models\UserPractica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PracticaEstudianteController extends Controller
{
    public function index()
    {
        return Inertia::render("Admin/PracticaEstudiantes/Index");
    }

    public function obtenerPracticaEstudiante(Practica $practica)
    {
        $user = Auth::user();
        $user_practica = UserPractica::where("user_id", $user->id)
            ->where("practica_id", $practica->id)
            ->get()->first();

        return response()->JSON($user_practica);
    }

    public function store(Request $request)
    {
        $request->validate([
            "practica_id" => "required",
            "codigo" => "required",
        ], ["codigo.required" => "Debes escribir un código"]);

        $practica = Practica::findOrFail($request->practica_id);
        $user = Auth::user();

        $datos = [
            "practica_id" => $practica->id,
            "user_id" => $user->id,
            "codigo" => $request->codigo,
            "correcto" => $request->correcto,
        ];

        //existe
        $user_practica = UserPractica::where("user_id", $user->id)
            ->where("practica_id", $practica->id)
            ->get()->first();

        DB::beginTransaction();
        try {
            if ($user_practica) {
                $user_practica->update($datos);
            } else {
                UserPractica::create($datos);
            }
            DB::commit();
            return redirect()->route("practica_estudiantes.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
