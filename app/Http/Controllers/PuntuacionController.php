<?php

namespace App\Http\Controllers;

use App\Models\Puntuacion;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PuntuacionController extends Controller
{
    public function index()
    {
        return Inertia::render("Admin/Puntuacions/Index");
    }

    public function getPuntuacions(Request $request)
    {
        $search = $request->input("search", "");
        $puntuacions = User::select("users.*", "puntuacions.id as puntuacion_id", "puntuacions.puntuacion")
            ->leftjoin("puntuacions", "puntuacions.user_id", "=", "users.id")
            ->where("users.tipo", "ESTUDIANTE");

        if (trim($search) != "") {
            $puntuacions->where("users.ci", $search);
        }
        $puntuacions = $puntuacions->get();
        return response()->JSON($puntuacions);
    }


    public function reiniciar(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::findOrFail($user_id);

        $puntuacion = null;
        if (!$user->puntuacion) {
            $user->puntuacion()->create([
                "puntuacion" => 0,
                "fecha_registro" => date("Y-m-d")
            ]);
        } else {
            $user->puntuacion->update([
                "puntuacion" => 0,
            ]);
        }
        $puntuacion = $user->puntuacion;

        return response()->JSON($puntuacion);
    }
}
