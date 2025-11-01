<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgresoController extends Controller
{
    public function index()
    {
        return Inertia::render("Admin/Progresos/Index");
    }

    public function getProgresos(Request $request)
    {
        $search = $request->input("search", "");
        $progresos = User::select("users.*", "progresos.id as progreso_id", "progresos.progreso")
            ->leftjoin("progresos", "progresos.user_id", "=", "users.id")
            ->where("users.tipo", "ESTUDIANTE");

        if (trim($search) != "") {
            $progresos->where("users.ci", $search);
        }
        $progresos = $progresos->get();
        return response()->JSON($progresos);
    }


    public function reiniciar(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::findOrFail($user_id);

        $progreso = null;
        if (!$user->progreso) {
            $user->progreso()->create([
                "progreso" => 0,
                "fecha_registro" => date("Y-m-d")
            ]);
        } else {
            $user->progreso->update([
                "progreso" => 0,
            ]);
        }
        $progreso = $user->progreso;

        return response()->JSON($progreso);
    }
}
