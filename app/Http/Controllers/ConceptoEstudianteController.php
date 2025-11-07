<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ConceptoEstudianteController extends Controller
{
    public function index()
    {
        $listSecciones = SeccionController::listSecciones();
        return Inertia::render("Admin/ConceptoEstudiantes/Index", compact("listSecciones"));
    }
}
