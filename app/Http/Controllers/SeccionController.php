<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SeccionController extends Controller
{
    public function index(Request $request)
    {
        $exep = [];
        if (isset($request->exep) && is_array($request->exep)) {
            $exep = $request->exep;
        }
        return response()->JSON(self::listSecciones($exep));
    }

    public static function listSecciones($exep = [])
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

        if (count($exep) > 0) {
            foreach ($exep as $key) {
                unset($secciones[$key]);
            }
        }


        return $secciones;
    }
}
