<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlgoritmoReglaController extends Controller
{
    /**
     * Evalúa el código fuente enviado por el estudiante.
     * Se compara contra reglas definidas para validar su estructura y calidad.
     */
    public function evaluarCodigo(Request $request)
    {
        $codigo = $request->input('codigo');
        $codigoCorrecto = $request->input('codigo_correcto');
        $reglas = $this->reglasEvaluacion();

        // Normalizar ambos códigos
        $lineasUsuario = $this->normalizarCodigo($codigo);
        $lineasCorrecto = $this->normalizarCodigo($codigoCorrecto);

        $total = count($lineasCorrecto);
        $correctas = 0;
        $detalle = [];

        foreach ($lineasUsuario as $i => $linea) {
            $esperado = $lineasCorrecto[$i] ?? "";
            $estado = "incorrecta";

            // Aplicar reglas
            foreach ($reglas as $nombre => $regla) {
                if ($regla($linea, $esperado)) {
                    $estado = "correcta";
                    $correctas++;
                    break;
                }
            }

            $detalle[] = [
                'linea' => $i + 1,
                'codigo_usuario' => $linea,
                'codigo_correcto' => $esperado,
                'estado' => $estado
            ];
        }

        $porcentaje = $total > 0 ? round(($correctas / $total) * 100, 2) : 0;

        return response()->json([
            'total' => $total,
            'correctas' => $correctas,
            'porcentaje' => $porcentaje,
            'detalle' => $detalle,
        ]);
    }

    /**
     * Cada regla es una función anónima que devuelve true si la línea pasa la condición.
     */
    private function reglasEvaluacion()
    {
        return [
            'igual_exacto' => function ($linea, $esperado) {
                return trim($linea) === trim($esperado);
            },
            'sin_espacios' => function ($linea, $esperado) {
                $a = preg_replace('/\s+/', '', $linea);
                $b = preg_replace('/\s+/', '', $esperado);
                return $a === $b;
            },
            'estructura' => function ($linea, $esperado) {
                $tokensEsperado = preg_split('/[^a-zA-Z0-9_]+/', strtolower($esperado));
                $tokensLinea = preg_split('/[^a-zA-Z0-9_]+/', strtolower($linea));
                $tokensEsperado = array_filter($tokensEsperado);
                $tokensLinea = array_filter($tokensLinea);

                $interseccion = count(array_intersect($tokensEsperado, $tokensLinea));
                return $interseccion > 0 && $interseccion >= count($tokensEsperado) * 0.8;
            },
        ];
    }

    /**
     * Limpia el código para comparaciones.
     */
    private function normalizarCodigo($codigo)
    {
        $codigo = str_replace(["\r\n", "\r"], "\n", $codigo);
        $lineas = explode("\n", $codigo);
        return array_map(fn($l) => trim($l), $lineas);
    }
}
