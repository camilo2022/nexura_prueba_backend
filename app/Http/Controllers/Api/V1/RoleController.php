<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleIndexRequest;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\Role\RoleIndexCollection;
use App\Models\Role;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class RoleController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function all()
    {
        try {
            $roles = Role::all();

            return $this->successResponse(
                $roles,
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

    public function index(RoleIndexRequest $request)
    {
        try {
            $roles = Role::when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new RoleIndexCollection($roles),
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

    public function store(RoleStoreRequest $request)
    {
        try {
            $role = new Role();
            $role->name = $request->input('name');
            $role->save();

            return $this->successResponse(
                $role,
                'El rol fue registrado exitosamente.',
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
            $role = Role::findOrFail($id);

            return $this->successResponse(
                $role,
                'El rol fue encontrado exitosamente.',
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

    public function update(RoleUpdateRequest $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->name = $request->input('name');
            $role->save();

            return $this->successResponse(
                $role,
                'El rol fue actualizado exitosamente.',
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
