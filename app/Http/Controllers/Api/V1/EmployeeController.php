<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeDeleteRequest;
use App\Http\Requests\Employee\EmployeeIndexRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Resources\Employee\EmployeeIndexCollection;
use App\Models\Employee;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class EmployeeController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index(EmployeeIndexRequest $request)
    {
        try {
            $employees = Employee::with('area')
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new EmployeeIndexCollection($employees),
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

    public function store(EmployeeStoreRequest $request)
    {
        try {
            $employee = new Employee();
            $employee->name = $request->input('name');
            $employee->email = $request->input('email');
            $employee->sex = $request->input('sex');
            $employee->area_id = $request->input('area_id');
            $employee->bulletin = $request->input('bulletin');
            $employee->description = $request->input('description');
            $employee->save();

            $employee->roles()->attach($request->input('role_ids'));

            return $this->successResponse(
                $employee,
                'El empleado fue registrado exitosamente.',
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
            $employee = Employee::with('area', 'roles')->findOrFail($id);

            return $this->successResponse(
                [
                    'employee' => $employee,
                    'role_ids' => $employee->roles()->pluck('id')
                ],
                'El empleado fue encontrado exitosamente.',
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

    public function update(EmployeeUpdateRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->name = $request->input('name');
            $employee->email = $request->input('email');
            $employee->sex = $request->input('sex');
            $employee->area_id = $request->input('area_id');
            $employee->bulletin = $request->input('bulletin');
            $employee->description = $request->input('description');
            $employee->save();

            $employee->roles()->sync($request->input('role_ids'));

            return $this->successResponse(
                $employee,
                'El empleado fue actualizado exitosamente.',
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

    public function delete(EmployeeDeleteRequest $request)
    {
        try {
            $employee = Employee::findOrFail($request->input('id'));

            $employee->roles()->detach();

            $employee->delete();

            return $this->successResponse(
                $employee,
                'El empleado fue eliminado exitosamente.',
                204
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
}
