<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConceptoStoreRequest;
use App\Http\Requests\ConceptoUpdateRequest;
use App\Http\Requests\ConceptoPasswordRequest;
use App\Models\Concepto;
use App\Services\ConceptoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ConceptoController extends Controller
{
    public function __construct(private ConceptoService $conceptoService) {}

    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/Conceptos/Index");
    }

    public function listado(Request $request): JsonResponse
    {
        $conceptos = Concepto::select("conceptos.*");

        if (isset($request->seccion) && $request->seccion) {
            $conceptos->where("seccion", $request->seccion);
        }

        $conceptos = $conceptos->get();
        return response()->JSON([
            "conceptos" => $conceptos
        ]);
    }

    public function api(Request $request)
    {
        $length = $request->input('length', 10); // Valor de `length` enviado por DataTable
        $start = $request->input('start', 0); // Índice de inicio enviado por DataTable
        $page = ($start / $length) + 1; // Cálculo de la página actual
        $search = $request->input('search');

        $conceptos = Concepto::select("conceptos.*");
        if ($search && trim($search) != '') {
            $conceptos->orWhereRaw("conceptos.seccion LIKE ?", ["%$search%"]);
            $conceptos->orWhereRaw("conceptos.titulo LIKE ?", ["%$search%"]);
            $conceptos->orWhereRaw("conceptos.url LIKE ?", ["%$search%"]);
        }

        // order
        if (isset($request->order)) {
            $order = $request->order;
            $nro_col = $order[0]["column"];
            $asc_desc = $order[0]["dir"];
            $columns = $request->columns;
            if ($columns[$nro_col]["data"]) {
                $col_data = $columns[$nro_col]["data"];
                $conceptos->orderBy($col_data, $asc_desc);
            }
        }

        $conceptos = $conceptos->paginate($length, ['*'], 'page', $page);

        // Numeración
        $conceptos->getCollection()->transform(function ($concepto, $index) use ($conceptos) {
            $concepto->enumeracion = ($conceptos->currentPage() - 1) * $conceptos->perPage() + $index + 1;
            return $concepto;
        });

        return response()->JSON([
            'data' => $conceptos->items(),
            'recordsTotal' => $conceptos->total(),
            'recordsFiltered' => $conceptos->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $conceptos = Concepto::where("id", "!=", 1);

        if (trim($search) != "") {
            $conceptos->where("nombre", "LIKE", "%$search%");
            $conceptos->orWhere("paterno", "LIKE", "%$search%");
            $conceptos->orWhere("materno", "LIKE", "%$search%");
            $conceptos->orWhere("ci", "LIKE", "%$search%");
        }

        $conceptos = $conceptos->WHERE("tipo", "ESTUDIANTE")->where("status", 1)->paginate($request->itemsPerPage);
        return response()->JSON([
            'data' => $conceptos->items(),
            'recordsTotal' => $conceptos->total(),
            'recordsFiltered' => $conceptos->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    /**
     * Store concepto
     *
     * @param ConceptoStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(ConceptoStoreRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->conceptoService->crear($request->validated());
            DB::commit();
            return redirect()->route("conceptos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Concepto $concepto)
    {
        return response()->JSON($concepto);
    }

    /**
     * Update concepto
     *
     * @param Concepto $concepto
     * @param ConceptoUpdateRequest $request
     * @return RedirectResponse|Response
     */
    public function update(Concepto $concepto, ConceptoUpdateRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->conceptoService->actualizar($request->validated(), $concepto);
            DB::commit();
            return redirect()->route("conceptos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete concepto
     *
     * @param Concepto $concepto
     * @return JsonResponse|Response
     */
    public function destroy(Concepto $concepto): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->conceptoService->eliminar($concepto);
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
