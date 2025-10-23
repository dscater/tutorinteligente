<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstudianteStoreRequest;
use App\Http\Requests\EstudianteUpdateRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Models\Estudiante;
use App\Models\User;
use App\Services\EstudianteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class EstudianteController extends Controller
{
    public function __construct(private EstudianteService $estudianteService) {}

    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/Estudiantes/Index");
    }

    public function listado(Request $request): JsonResponse
    {
        $estudiantes = User::where("id", "!=", 1);

        if (isset($request->tipo) && $request->tipo) {
            $estudiantes->where("tipo", $request->tipo);
        }

        $estudiantes = $estudiantes->where("status", 1)->get();
        return response()->JSON([
            "estudiantes" => $estudiantes
        ]);
    }

    public function api(Request $request)
    {
        $length = $request->input('length', 10); // Valor de `length` enviado por DataTable
        $start = $request->input('start', 0); // Índice de inicio enviado por DataTable
        $page = ($start / $length) + 1; // Cálculo de la página actual
        $search = $request->input('search');

        $estudiantes = User::selectRaw("users.*, CONCAT(users.nombre,' ',users.paterno,' ',users.materno) as full_name, CONCAT(users.ci,' ',users.ci_exp) as full_ci")
            ->where("users.id", "!=", 1);
        if ($search && trim($search) != '') {
            $estudiantes->orWhereRaw("users.estudiante LIKE ?", ["%$search%"]);
            $estudiantes->orWhereRaw("CONCAT(users.nombres,' ',users.paterno,' ',users.materno) LIKE ?", ["%$search%"]);
        }

        // order
        if (isset($request->order)) {
            $order = $request->order;
            $nro_col = $order[0]["column"];
            $asc_desc = $order[0]["dir"];
            $columns = $request->columns;
            if ($columns[$nro_col]["data"]) {
                $col_data = $columns[$nro_col]["data"];
                $estudiantes->orderBy($col_data, $asc_desc);
            }
        }

        $estudiantes = $estudiantes->where("tipo", "ESTUDIANTE")->where("status", 1)->paginate($length, ['*'], 'page', $page);

        // Numeración
        $estudiantes->getCollection()->transform(function ($estudiante, $index) use ($estudiantes) {
            $estudiante->enumeracion = ($estudiantes->currentPage() - 1) * $estudiantes->perPage() + $index + 1;
            return $estudiante;
        });

        return response()->JSON([
            'data' => $estudiantes->items(),
            'recordsTotal' => $estudiantes->total(),
            'recordsFiltered' => $estudiantes->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $estudiantes = User::where("id", "!=", 1);

        if (trim($search) != "") {
            $estudiantes->where("nombre", "LIKE", "%$search%");
            $estudiantes->orWhere("paterno", "LIKE", "%$search%");
            $estudiantes->orWhere("materno", "LIKE", "%$search%");
            $estudiantes->orWhere("ci", "LIKE", "%$search%");
        }

        $estudiantes = $estudiantes->WHERE("tipo", "ESTUDIANTE")->where("status", 1)->paginate($request->itemsPerPage);
        return response()->JSON([
            'data' => $estudiantes->items(),
            'recordsTotal' => $estudiantes->total(),
            'recordsFiltered' => $estudiantes->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    /**
     * Store estudiante
     *
     * @param EstudianteStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(EstudianteStoreRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->estudianteService->crear($request->validated());
            DB::commit();
            return redirect()->route("estudiantes.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(User $estudiante)
    {
        return response()->JSON($estudiante);
    }

    /**
     * Update estudiante
     *
     * @param User $estudiante
     * @param EstudianteUpdateRequest $request
     * @return RedirectResponse|Response
     */
    public function update(User $estudiante, EstudianteUpdateRequest $request): RedirectResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->estudianteService->actualizar($request->validated(), $estudiante);
            DB::commit();
            return redirect()->route("estudiantes.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete estudiante
     *
     * @param User $estudiante
     * @return JsonResponse|Response
     */
    public function destroy(User $estudiante): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->estudianteService->eliminar($estudiante);
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

    public function actualizaPassword(User $user, UserPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->estudianteService->actualizarPassword($request->validated(), $user);
            DB::commit();
            return redirect()->route("estudiantes.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
