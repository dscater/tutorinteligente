<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\PracticaController;
use App\Http\Controllers\PracticaEstudianteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
});

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    return 'Cache eliminado <a href="/">Ir al inicio</a>';
})->name('clear.cache');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("login");

Route::post('/registro/validaForm1', [RegisteredUserController::class, 'validaForm1'])->name("registro.validaForm1");
Route::get('/registro', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Register');
})->name("registro");

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::get("secciones", [SeccionController::class, 'index'])->name("secciones.index");
Route::get("nivels", [NivelController::class, 'index'])->name("nivels.index");

// ADMINISTRACION
Route::middleware(['auth', 'permisoUsuario'])->prefix("admin")->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');

    // CONFIGURACION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisosUsuario", [UserController::class, 'permisosUsuario']);

    // USUARIOS
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/api", [UsuarioController::class, 'api'])->name("usuarios.api");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // ESTUDIANTES
    Route::put("estudiantes/password/{user}", [EstudianteController::class, 'actualizaPassword'])->name("estudiantes.password");
    Route::get("estudiantes/api", [EstudianteController::class, 'api'])->name("estudiantes.api");
    Route::get("estudiantes/paginado", [EstudianteController::class, 'paginado'])->name("estudiantes.paginado");
    Route::get("estudiantes/listado", [EstudianteController::class, 'listado'])->name("estudiantes.listado");
    Route::resource("estudiantes", EstudianteController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // CONCEPTOS
    Route::get("conceptos/api", [ConceptoController::class, 'api'])->name("conceptos.api");
    Route::get("conceptos/paginado", [ConceptoController::class, 'paginado'])->name("conceptos.paginado");
    Route::get("conceptos/listado", [ConceptoController::class, 'listado'])->name("conceptos.listado");
    Route::resource("conceptos", ConceptoController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // CUESTIONARIOS
    Route::get("cuestionarios/api", [CuestionarioController::class, 'api'])->name("cuestionarios.api");
    Route::get("cuestionarios/paginado", [CuestionarioController::class, 'paginado'])->name("cuestionarios.paginado");
    Route::get("cuestionarios/listado", [CuestionarioController::class, 'listado'])->name("cuestionarios.listado");
    Route::resource("cuestionarios", CuestionarioController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // PRACTICAS
    Route::get("practicas/api", [PracticaController::class, 'api'])->name("practicas.api");
    Route::get("practicas/paginado", [PracticaController::class, 'paginado'])->name("practicas.paginado");
    Route::get("practicas/listado", [PracticaController::class, 'listado'])->name("practicas.listado");
    Route::resource("practicas", PracticaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // PRACTICAS-ESTUDIANTES
    Route::get("practica_estudiantes", [PracticaEstudianteController::class, 'index'])->name("practica_estudiantes.index");
    Route::get("practica_estudiantes/obtenerPracticaEstudiante/{practica}", [PracticaEstudianteController::class, 'obtenerPracticaEstudiante'])->name("practica_estudiantes.getPractica");
    Route::post("practica_estudiantes", [PracticaEstudianteController::class, 'store'])->name("practica_estudiantes.store");

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");

    Route::get('reportes/pacientes', [ReporteController::class, 'pacientes'])->name("reportes.pacientes");
    Route::get('reportes/r_pacientes', [ReporteController::class, 'r_pacientes'])->name("reportes.r_pacientes");
});
require __DIR__ . '/auth.php';
