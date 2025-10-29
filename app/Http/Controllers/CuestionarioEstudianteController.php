<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CuestionarioEstudianteController extends Controller
{
    public function index()
    {
        $listSecciones = SeccionController::listSecciones();
        return Inertia::render("Admin/CuestionarioEstudiantes/Index", compact("listSecciones"));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cuestionarios = $request->cuestionarios;
            $total = count($cuestionarios);
            $correctos = 0;
            foreach ($cuestionarios as $item) {
                $cuestionario = Cuestionario::findOrFail($item['cuestionario_id']);
                if ($item['valor'] == $cuestionario->correcta) {
                    $correctos++;
                }
            }

            $user = Auth::user();
            // REGISTRAR PUNTAJE
            $puntuacion = 0;
            $obtenido = 0;
            if (!$user->puntuacion) {
                $puntuacion = ($correctos * 100) / $total;
                $puntuacion = round($puntuacion, 0);
                $user->puntuacion()->create([
                    "puntuacion" => $puntuacion,
                    "fecha_registro" => date("Y-m-d"),
                ]);
                $obtenido = $puntuacion;
            } else {
                $actual_puntaje = (float)$user->puntuacion->puntuacion;
                $puntuacion = ($correctos * 100) / $total;
                $puntuacion = round($puntuacion, 0);
                $obtenido = $puntuacion;
                $puntuacion += $actual_puntaje;
                $user->puntuacion->update([
                    "puntuacion" => $puntuacion,
                ]);
            }

            DB::commit();
            return redirect()->route("cuestionario_estudiantes.index")->with("bien", "Puntaje Obtenido: <h4>" . $obtenido . "</h4>");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function getPuntuacion(User $user)
    {
        return response()->JSON([
            "puntuacion" => $user->puntuacion
        ]);
    }
}
