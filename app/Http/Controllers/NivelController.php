<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NivelController extends Controller
{
    public function index()
    {
        return response()->JSON(self::listNivels());
    }

    public static function listNivels()
    {
        $secciones = [
            [
                "value" => "BÁSICO",
                "label" => "BÁSICO",
            ],
            [
                "value" => "MEDIO",
                "label" => "MEDIO",
            ],
            [
                "value" => "AVANZADO",
                "label" => "AVANZADO",
            ],
        ];

        return $secciones;
    }
}
