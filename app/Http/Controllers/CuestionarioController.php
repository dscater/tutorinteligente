<?php

namespace App\Http\Controllers;

use App\Http\Requests\CuestionarioStoreRequest;
use App\Http\Requests\CuestionarioUpdateRequest;
use App\Http\Requests\CuestionarioPasswordRequest;
use App\Models\Cuestionario;
use App\Services\CuestionarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CuestionarioController extends Controller
{
    public function __construct(private CuestionarioService $cuestionarioService) {}

    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/Cuestionarios/Index");
    }

    public function listado(Request $request): JsonResponse
    {
        $cuestionarios = Cuestionario::select("cuestionarios.*");

        if (isset($request->seccion) && $request->seccion) {
            $cuestionarios->where("seccion", $request->seccion);
        }

        if (isset($request->random)) {
            // Aleatoriza el orden
            $cuestionarios = $cuestionarios->inRandomOrder()->get();
        } else {
            $cuestionarios = $cuestionarios->get();
        }
        return response()->JSON([
            "cuestionarios" => $cuestionarios
        ]);
    }

    public function api(Request $request)
    {
        $length = $request->input('length', 10); // Valor de `length` enviado por DataTable
        $start = $request->input('start', 0); // Índice de inicio enviado por DataTable
        $page = ($start / $length) + 1; // Cálculo de la página actual
        $search = $request->input('search');

        $cuestionarios = Cuestionario::select("cuestionarios.*");
        if ($search && trim($search) != '') {
            $cuestionarios->orWhereRaw("cuestionarios.seccion LIKE ?", ["%$search%"]);
            $cuestionarios->orWhereRaw("cuestionarios.pregunta LIKE ?", ["%$search%"]);
        }

        // order
        if (isset($request->order)) {
            $order = $request->order;
            $nro_col = $order[0]["column"];
            $asc_desc = $order[0]["dir"];
            $columns = $request->columns;
            if ($columns[$nro_col]["data"]) {
                $col_data = $columns[$nro_col]["data"];
                $cuestionarios->orderBy($col_data, $asc_desc);
            }
        }

        $cuestionarios = $cuestionarios->paginate($length, ['*'], 'page', $page);

        // Numeración
        $cuestionarios->getCollection()->transform(function ($cuestionario, $index) use ($cuestionarios) {
            $cuestionario->enumeracion = ($cuestionarios->currentPage() - 1) * $cuestionarios->perPage() + $index + 1;
            return $cuestionario;
        });

        return response()->JSON([
            'data' => $cuestionarios->items(),
            'recordsTotal' => $cuestionarios->total(),
            'recordsFiltered' => $cuestionarios->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $cuestionarios = Cuestionario::select("cuestionarios.*");

        if (trim($search) != "") {
            $cuestionarios->where("nombre", "LIKE", "%$search%");
            $cuestionarios->orWhere("paterno", "LIKE", "%$search%");
            $cuestionarios->orWhere("materno", "LIKE", "%$search%");
            $cuestionarios->orWhere("ci", "LIKE", "%$search%");
        }

        $cuestionarios = $cuestionarios->WHERE("tipo", "ESTUDIANTE")->paginate($request->itemsPerPage);
        return response()->JSON([
            'data' => $cuestionarios->items(),
            'recordsTotal' => $cuestionarios->total(),
            'recordsFiltered' => $cuestionarios->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    /**
     * Store cuestionario
     *
     * @param CuestionarioStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(CuestionarioStoreRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->cuestionarioService->crear($request->validated());
            DB::commit();
            return redirect()->route("cuestionarios.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Cuestionario $cuestionario)
    {
        return response()->JSON($cuestionario);
    }

    /**
     * Update cuestionario
     *
     * @param Cuestionario $cuestionario
     * @param CuestionarioUpdateRequest $request
     * @return RedirectResponse|Response
     */
    public function update(Cuestionario $cuestionario, CuestionarioUpdateRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->cuestionarioService->actualizar($request->validated(), $cuestionario);
            DB::commit();
            return redirect()->route("cuestionarios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete cuestionario
     *
     * @param Cuestionario $cuestionario
     * @return JsonResponse|Response
     */
    public function destroy(Cuestionario $cuestionario): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->cuestionarioService->eliminar($cuestionario);
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
