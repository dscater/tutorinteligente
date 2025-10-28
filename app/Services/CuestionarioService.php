<?php

namespace App\Services;

use App\Models\Cuestionario;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CuestionarioService
{
    private $modulo = "CUESTIONARIOS";

    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}

    /**
     * Crear cuestionario
     *
     * @param array $datos
     * @return Cuestionario
     */
    public function crear(array $datos): Cuestionario
    {
        $cuestionario = Cuestionario::create([
            "seccion" => mb_strtoupper($datos["seccion"]),
            "pregunta" => mb_strtoupper($datos["pregunta"]),
            "resp1" => mb_strtoupper($datos["resp1"]),
            "resp2" => mb_strtoupper($datos["resp2"]),
            "resp3" => mb_strtoupper($datos["resp3"]),
            "resp4" => mb_strtoupper($datos["resp4"]),
            "correcta" => $datos["correcta"],
            "fecha_registro" => date("Y-m-d")
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN CUESTIONARIO", $cuestionario);

        return $cuestionario;
    }

    /**
     * Actualizar cuestionario
     *
     * @param array $datos
     * @param Cuestionario $cuestionario
     * @return Cuestionario
     */
    public function actualizar(array $datos, Cuestionario $cuestionario): Cuestionario
    {
        $old_cuestionario = Cuestionario::find($cuestionario->id);

        $cuestionario->update([
            "seccion" => mb_strtoupper($datos["seccion"]),
            "pregunta" => mb_strtoupper($datos["pregunta"]),
            "resp1" => mb_strtoupper($datos["resp1"]),
            "resp2" => mb_strtoupper($datos["resp2"]),
            "resp3" => mb_strtoupper($datos["resp3"]),
            "resp4" => mb_strtoupper($datos["resp4"]),
            "correcta" => $datos["correcta"],
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN CUESTIONARIO", $old_cuestionario, $cuestionario->withoutRelations());

        return $cuestionario;
    }

    /**
     * Eliminar cuestionario
     *
     * @param Cuestionario $cuestionario
     * @return boolean
     */
    public function eliminar(Cuestionario $cuestionario): bool
    {
        // no eliminar cuestionarios predeterminados para el funcionamiento del sistema
        $old_cuestionario = clone $cuestionario;
        $cuestionario->delete();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN CUESTIONARIO", $old_cuestionario, $cuestionario);
        return true;
    }
}
