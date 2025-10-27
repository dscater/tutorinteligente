<?php

namespace App\Services;

use App\Models\Concepto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ConceptoService
{
    private $modulo = "CONCEPTOS";

    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}

    /**
     * Crear concepto
     *
     * @param array $datos
     * @return Concepto
     */
    public function crear(array $datos): Concepto
    {
        $concepto = Concepto::create([
            "seccion" => mb_strtoupper($datos["seccion"]),
            "titulo" => mb_strtoupper($datos["titulo"]),
            "descripcion" => $datos["descripcion"],
            "url" => $datos["url"],
            "fecha_registro" => date("Y-m-d")
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN CONCEPTO", $concepto);

        return $concepto;
    }

    /**
     * Actualizar concepto
     *
     * @param array $datos
     * @param Concepto $concepto
     * @return Concepto
     */
    public function actualizar(array $datos, Concepto $concepto): Concepto
    {
        $old_concepto = Concepto::find($concepto->id);

        $concepto->update([
            "seccion" => mb_strtoupper($datos["seccion"]),
            "titulo" => mb_strtoupper($datos["titulo"]),
            "descripcion" => $datos["descripcion"],
            "url" => $datos["url"],
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN CONCEPTO", $old_concepto, $concepto->withoutRelations());

        return $concepto;
    }

    /**
     * Eliminar concepto
     *
     * @param Concepto $concepto
     * @return boolean
     */
    public function eliminar(Concepto $concepto): bool
    {
        // no eliminar conceptos predeterminados para el funcionamiento del sistema
        $old_concepto = clone $concepto;
        $concepto->delete();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ EL CONCEPTO " . $concepto->full_name, $old_concepto, $concepto);
        return true;
    }
}
