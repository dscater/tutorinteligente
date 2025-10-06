<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermisoService
{
    protected $arrayPermisos = [
        "ADMINISTRADOR" => [
            "usuarios.api",
            "usuarios.index",
            "usuarios.listado",
            "usuarios.create",
            "usuarios.store",
            "usuarios.edit",
            "usuarios.show",
            "usuarios.update",
            "usuarios.destroy",
            "usuarios.password",

            "estudiantes.api",
            "estudiantes.listado",
            "estudiantes.index",
            "estudiantes.create",
            "estudiantes.store",
            "estudiantes.edit",
            "estudiantes.show",
            "estudiantes.update",
            "estudiantes.destroy",

            "conceptos.api",
            "conceptos.listado",
            "conceptos.index",
            "conceptos.create",
            "conceptos.store",
            "conceptos.edit",
            "conceptos.show",
            "conceptos.update",
            "conceptos.destroy",

            "cuestionarios.api",
            "cuestionarios.listado",
            "cuestionarios.index",
            "cuestionarios.create",
            "cuestionarios.store",
            "cuestionarios.edit",
            "cuestionarios.show",
            "cuestionarios.update",
            "cuestionarios.destroy",

            "puntuacions.api",
            "puntuacions.listado",
            "puntuacions.index",
            "puntuacions.create",
            "puntuacions.store",
            "puntuacions.edit",
            "puntuacions.show",
            "puntuacions.update",
            "puntuacions.destroy",

            "practicas.api",
            "practicas.listado",
            "practicas.index",
            "practicas.create",
            "practicas.store",
            "practicas.edit",
            "practicas.show",
            "practicas.update",
            "practicas.destroy",

            "progresos.api",
            "progresos.listado",
            "progresos.index",
            "progresos.create",
            "progresos.store",
            "progresos.edit",
            "progresos.show",
            "progresos.update",
            "progresos.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.update",
            "configuracions.destroy",

            "reportes.usuarios",
            "reportes.r_usuarios",
            "reportes.estudiantes",
            "reportes.r_estudiantes",
        ],
        "EMPLEADO" => [],
    ];

    public function getPermisosUser()
    {
        $user = Auth::user();
        $permisos = [];
        if ($user) {
            return $this->arrayPermisos[$user->tipo];
        }

        return $permisos;
    }
}
