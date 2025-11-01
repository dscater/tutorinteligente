<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Material;
use App\Models\Producto;
use App\Models\Publicacion;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function permisosUsuario(Request $request)
    {
        return response()->JSON([
            "permisos" => Auth::user()->permisos
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }

    public static function getInfoBoxUser()
    {
        $permisos = [];
        $array_infos = [];
        if (Auth::check()) {
            $oUser = new User();
            $permisos = $oUser->permisos;
            $tipo = Auth::user()->tipo;
            if ($permisos == '*' || (is_array($permisos) && in_array('usuarios.index', $permisos))) {
                $array_infos[] = [
                    'label' => 'USUARIOS',
                    'cantidad' => User::where('id', '!=', 1)->where("tipo", "ADMINISTRADOR")->count(),
                    'color' => 'bg-principal',
                    'icon' => "fa-users",
                    "url" => "usuarios.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('usuarios.index', $permisos))) {
                $array_infos[] = [
                    'label' => 'ESTUDIANTES',
                    'cantidad' => User::where('id', '!=', 1)->where("tipo", "ESTUDIANTE")->count(),
                    'color' => 'bg-principal',
                    'icon' => "fa-user-friends",
                    "url" => "usuarios.index"
                ];
            }

            if ($tipo == 'ESTUDIANTE') {
                $puntuacion = 0;
                $user = Auth::user();
                if ($user->puntuacion) {
                    $puntuacion = $user->puntuacion->puntuacion;
                }

                $array_infos[] = [
                    'label' => 'PUNTUACIÓN',
                    'cantidad' =>  $puntuacion,
                    'color' => 'bg-principal',
                    'icon' => "fa-clipboard-check",
                    "url" => "cuestionario_estudiantes.index"
                ];


                $progreso = 0;
                $user = Auth::user();
                if ($user->progreso) {
                    $progreso = $user->progreso->progreso;
                }

                $array_infos[] = [
                    'label' => 'PROGRESO',
                    'cantidad' =>  $progreso . '%',
                    'color' => 'bg-principal',
                    'icon' => "fa-chart-line",
                    "url" => "practica_estudiantes.index"
                ];
            }
        }
        return $array_infos;
    }
}
