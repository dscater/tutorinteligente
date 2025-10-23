<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EstudianteService
{
    private $modulo = "ESTUDIANTES";

    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}

    /**
     * Crear estudiante
     *
     * @param array $datos
     * @return User
     */
    public function crear(array $datos): User
    {
        $estudiante = User::create([
            "usuario" => $datos["correo"],
            "nombre" => mb_strtoupper($datos["nombre"]),
            "paterno" => mb_strtoupper($datos["paterno"]),
            "materno" => mb_strtoupper($datos["materno"]),
            "dir" => mb_strtoupper($datos["dir"]),
            "ci" => $datos["ci"],
            "ci_exp" => $datos["ci_exp"],
            "fono" => $datos["fono"],
            "correo" => $datos["correo"],
            "password" => $datos["ci"],
            "tipo" => "ESTUDIANTE",
            "acceso" => $datos["acceso"],
            "fecha_registro" => date("Y-m-d")
        ]);

        // cargar foto
        if ($datos["foto"] && !is_string($datos["foto"])) {
            $this->cargarFoto($estudiante, $datos["foto"]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN ESTUDIANTE", $estudiante);

        return $estudiante;
    }

    /**
     * Actualizar estudiante
     *
     * @param array $datos
     * @param User $estudiante
     * @return User
     */
    public function actualizar(array $datos, User $estudiante): User
    {
        $old_estudiante = User::find($estudiante->id);

        $estudiante->update([
            "usuario" => $datos["correo"],
            "nombre" => mb_strtoupper($datos["nombre"]),
            "paterno" => mb_strtoupper($datos["paterno"]),
            "materno" => mb_strtoupper($datos["materno"]),
            "dir" => mb_strtoupper($datos["dir"]),
            "ci" => $datos["ci"],
            "ci_exp" => $datos["ci_exp"],
            "fono" => $datos["fono"],
            "correo" => $datos["correo"],
            "acceso" => $datos["acceso"],
            "fecha_registro" => date("Y-m-d")
        ]);

        // cargar foto
        if ($datos["foto"] && !is_string($datos["foto"])) {
            $this->cargarFoto($estudiante, $datos["foto"]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN ESTUDIANTE", $old_estudiante, $estudiante->withoutRelations());

        return $estudiante;
    }

    /**
     * Actualizar password
     *
     * @param array $datos
     * @param User $estudiante
     * @return User
     */
    public function actualizarPassword(array $datos, User $estudiante): User
    {
        $estudiante->password = Hash::make($datos["password"]);
        $estudiante->save();
        return $estudiante;
    }

    /**
     * Cargar foto
     *
     * @param User $estudiante
     * @param UploadedFile $foto
     * @return void
     */
    public function cargarFoto(User $estudiante, UploadedFile $foto): void
    {
        if ($estudiante->foto) {
            \File::delete(public_path("imgs/users/" . $this->estudiante->foto));
        }

        $nombre = $estudiante->id . time();
        $estudiante->foto = $this->cargarArchivoService->cargarArchivo($foto, public_path("imgs/users"), $nombre);
        $estudiante->save();
    }

    /**
     * Eliminar estudiante
     *
     * @param User $estudiante
     * @return boolean
     */
    public function eliminar(User $estudiante): bool
    {
        // no eliminar estudiantes predeterminados para el funcionamiento del sistema
        $old_estudiante = User::find($estudiante->id);

        $estudiante->status = 0;
        $estudiante->save();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ AL ESTUDIANTE " . $estudiante->full_name, $old_estudiante, $estudiante);
        return true;
    }
}
