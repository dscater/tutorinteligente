<?php

namespace App\Http\Controllers;

use App\Http\Requests\PracticaStoreRequest;
use App\Http\Requests\PracticaUpdateRequest;
use App\Http\Requests\PracticaPasswordRequest;
use App\Models\Practica;
use App\Services\PracticaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PracticaController extends Controller
{
    public function __construct(private PracticaService $practicaService) {}

    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/Practicas/Index");
    }

    public function listado(Request $request): JsonResponse
    {
        $practicas = Practica::select("practicas.*");

        if (isset($request->nivel) && $request->nivel) {
            $practicas->where("nivel", $request->nivel);
        }

        if (isset($request->seccion) && $request->seccion) {
            $practicas->where("seccion", $request->seccion);
        }

        $practicas = $practicas->get();
        return response()->JSON([
            "practicas" => $practicas
        ]);
    }

    public function api(Request $request)
    {
        $length = $request->input('length', 10); // Valor de `length` enviado por DataTable
        $start = $request->input('start', 0); // Índice de inicio enviado por DataTable
        $page = ($start / $length) + 1; // Cálculo de la página actual
        $search = $request->input('search');

        $practicas = Practica::select("practicas.*");
        if ($search && trim($search) != '') {
            $practicas->orWhereRaw("practicas.seccion LIKE ?", ["%$search%"]);
            $practicas->orWhereRaw("practicas.pregunta LIKE ?", ["%$search%"]);
        }

        // order
        if (isset($request->order)) {
            $order = $request->order;
            $nro_col = $order[0]["column"];
            $asc_desc = $order[0]["dir"];
            $columns = $request->columns;
            if ($columns[$nro_col]["data"]) {
                $col_data = $columns[$nro_col]["data"];
                $practicas->orderBy($col_data, $asc_desc);
            }
        }

        $practicas = $practicas->paginate($length, ['*'], 'page', $page);

        // Numeración
        $practicas->getCollection()->transform(function ($practica, $index) use ($practicas) {
            $practica->enumeracion = ($practicas->currentPage() - 1) * $practicas->perPage() + $index + 1;
            return $practica;
        });

        return response()->JSON([
            'data' => $practicas->items(),
            'recordsTotal' => $practicas->total(),
            'recordsFiltered' => $practicas->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $practicas = Practica::select("practicas.*");

        if (trim($search) != "") {
            $practicas->where("nombre", "LIKE", "%$search%");
            $practicas->orWhere("paterno", "LIKE", "%$search%");
            $practicas->orWhere("materno", "LIKE", "%$search%");
            $practicas->orWhere("ci", "LIKE", "%$search%");
        }

        $practicas = $practicas->WHERE("tipo", "ESTUDIANTE")->where("status", 1)->paginate($request->itemsPerPage);
        return response()->JSON([
            'data' => $practicas->items(),
            'recordsTotal' => $practicas->total(),
            'recordsFiltered' => $practicas->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    /**
     * Store practica
     *
     * @param PracticaStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(PracticaStoreRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->practicaService->crear($request->validated());
            DB::commit();
            return redirect()->route("practicas.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Practica $practica)
    {
        return response()->JSON($practica);
    }

    /**
     * Update practica
     *
     * @param Practica $practica
     * @param PracticaUpdateRequest $request
     * @return RedirectResponse|Response
     */
    public function update(Practica $practica, PracticaUpdateRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->practicaService->actualizar($request->validated(), $practica);
            DB::commit();
            return redirect()->route("practicas.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete practica
     *
     * @param Practica $practica
     * @return JsonResponse|Response
     */
    public function destroy(Practica $practica): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->practicaService->eliminar($practica);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
