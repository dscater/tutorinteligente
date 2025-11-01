<?php

namespace App\Services;

use App\Models\Practica;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PracticaService
{
    private $modulo = "PRÁCTICAS";

    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}

    /**
     * Crear practica
     *
     * @param array $datos
     * @return Practica
     */
    public function crear(array $datos): Practica
    {
        $practica = Practica::create([
            "nivel" => mb_strtoupper($datos["nivel"]),
            "seccion" => mb_strtoupper($datos["seccion"]),
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "codigo" => $datos["codigo"],
            "lineas" => $datos["lineas"],
            "fecha_registro" => date("Y-m-d")
        ]);



        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA PRÁCTICA", $practica);

        return $practica;
    }

    /**
     * Actualizar practica
     *
     * @param array $datos
     * @param Practica $practica
     * @return Practica
     */
    public function actualizar(array $datos, Practica $practica): Practica
    {
        $old_practica = Practica::find($practica->id);

        $practica->update([
            "nivel" => mb_strtoupper($datos["nivel"]),
            "seccion" => mb_strtoupper($datos["seccion"]),
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "codigo" => $datos["codigo"],
            "lineas" => $datos["lineas"],
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA PRÁCTICA", $old_practica, $practica->withoutRelations());

        return $practica;
    }

    /**
     * Eliminar practica
     *
     * @param Practica $practica
     * @return boolean
     */
    public function eliminar(Practica $practica): bool
    {
        // no eliminar practicas predeterminados para el funcionamiento del sistema
        $old_practica = clone $practica;
        $practica->delete();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA PRÁCTICA ", $old_practica, $practica);
        return true;
    }
}
