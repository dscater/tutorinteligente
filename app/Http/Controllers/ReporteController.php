<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\HistorialOferta;
use App\Models\Publicacion;
use App\Models\PublicacionDetalle;
use App\Models\SubastaCliente;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PDF;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Admin/Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $tipo =  $request->tipo;
        $usuarios = User::select("users.*")
            ->where('tipo', '!=', 'ESTUDIANTE')
            ->where('id', '!=', 1);

        if ($tipo != 'todos') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios->where('tipo', $tipo);
        }

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }
    public function estudiantes()
    {
        return Inertia::render("Admin/Reportes/Estudiantes");
    }
    public function r_estudiantes(Request $request)
    {
        $estudiante_id =  $request->estudiante_id;
        $usuarios = User::select("users.*")
            ->where('tipo', '=', 'ESTUDIANTE');

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        $pdf = PDF::loadView('reportes.estudiantes', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('estudiantes.pdf');
    }

    public function puntuacion_progresos()
    {
        return Inertia::render("Admin/Reportes/PuntuacionProgresos");
    }
    public function r_puntuacion_progresos(Request $request)
    {
        $estudiante_id =  $request->estudiante_id;
        $usuarios = User::select("users.*")
            ->where('tipo', '=', 'ESTUDIANTE');

        if ($estudiante_id != 'todos') {
            $usuarios->where("id", $estudiante_id);
        }

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        $pdf = PDF::loadView('reportes.puntuacion_progresos', compact('usuarios'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('puntuacion_progresos.pdf');
    }


    public function gpuntuacion_progresos()
    {
        return Inertia::render("Admin/Reportes/GPuntuacionProgresos");
    }
    public function r_gpuntuacion_progresos(Request $request)
    {
        // $estudiante_id =  $request->estudiante_id;
        $usuarios = User::select("users.*")
            ->where('tipo', '=', 'ESTUDIANTE');

        // if ($estudiante_id != 'todos') {
        //     $usuarios->where("id", $estudiante_id);
        // }

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        $categories = [];
        $data = [
            [
                "name" => "Puntuación",
                "data" => [],
                "color" => "#348fe2",
            ],
            [
                "name" => "Progreso %",
                "data" => [],
                "color" => "#53ba83",
            ]
        ];

        foreach ($usuarios as $usuario) {
            $categories[] = $usuario->full_name;
            $puntaje = 0;
            $progreso = 0;
            if ($usuario->puntuacion) {
                $puntaje = $usuario->puntuacion->puntuacion;
            }

            if ($usuario->progreso) {
                $progreso = $usuario->progreso->progreso;
            }
            $data[0]["data"][] = $puntaje;
            $data[1]["data"][] = $progreso;
        }

        return response()->JSON([
            "categories" => $categories,
            "data" => $data,
        ]);
    }
}
