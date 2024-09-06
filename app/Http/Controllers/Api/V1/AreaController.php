<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Area\AreaIndexRequest;
use App\Http\Requests\Area\AreaStoreRequest;
use App\Http\Requests\Area\AreaUpdateRequest;
use App\Http\Resources\Area\AreaIndexCollection;
use App\Models\Area;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class AreaController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function all()
    {
        try {
            $areas = Area::all();

            return $this->successResponse(
                $areas,
                $this->getMessage('Success'),
                200
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function index(AreaIndexRequest $request)
    {
        try {
            $areas = Area::when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new AreaIndexCollection($areas),
                $this->getMessage('Success'),
                200
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(AreaStoreRequest $request)
    {
        try {
            $area = new Area();
            $area->name = $request->input('name');
            $area->save();

            return $this->successResponse(
                $area,
                'El area fue registrado exitosamente.',
                201
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit($id)
    {
        try {
            $area = Area::findOrFail($id);

            return $this->successResponse(
                $area,
                'El area fue encontrado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            // Captura y maneja excepciones cuando no se encuentra el modelo
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(AreaUpdateRequest $request, $id)
    {
        try {
            $area = Area::findOrFail($id);
            $area->name = $request->input('name');
            $area->save();

            return $this->successResponse(
                $area,
                'El area fue actualizado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            // Captura y maneja excepciones cuando no se encuentra el modelo
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
