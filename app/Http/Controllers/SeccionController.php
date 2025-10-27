<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index()
    {
        return response()->JSON(self::listSecciones());
    }

    public static function listSecciones()
    {
        $secciones = [
            [
                "value" => "INTRODUCCIÓN",
                "label" => "INTRODUCCIÓN",
            ],
            [
                "value" => "ABSTRACCIÓN",
                "label" => "ABSTRACCIÓN",
            ],
            [
                "value" => "ENCAPSULAMIENTO",
                "label" => "ENCAPSULAMIENTO",
            ],
            [
                "value" => "HERENCIA",
                "label" => "HERENCIA",
            ],
            [
                "value" => "POLIMORFISMO",
                "label" => "POLIMORFISMO",
            ],
            [
                "value" => "CLASES Y OBJETOS",
                "label" => "CLASES Y OBJETOS",
            ],
            [
                "value" => "MÉTODOS Y ATRIBUTOS",
                "label" => "MÉTODOS Y ATRIBUTOS",
            ],
            [
                "value" => "RELACIÓN ENTRE CLASES",
                "label" => "RELACIÓN ENTRE CLASES",
            ]
        ];

        return $secciones;
    }
}
