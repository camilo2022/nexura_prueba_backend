<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeStoreRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación.',
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'sex' => ['required', 'in:M,F'],
            'area_id' => ['required', 'exists:areas,id'],
            'bulletin' => ['required', 'in:1,0', 'numeric'],
            'description' => ['required'],
            'role_ids' => ['required', 'array', 'min:1'],
            'role_ids.*' => ['required', 'numeric', 'exists:roles,id']
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
            'in' => 'El campo :attribute debe ser un valor valido (:in).',
            'exists' => 'El campo :attribute no existe en la base de datos.',
            'numeric' => 'El campo :attribute debe ser un valor numerico.',
            'array' => 'El campo :attribute debe ser un arreglo.'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del empleado',
            'email' => 'correo del empleado',
            'sex' => 'sexo del empleado',
            'area_id' => 'identificador del area del empleado',
            'bulletin' => 'desea recibir boletin informativo',
            'description' => 'descripcion de la experiencia del empleado',
            'role_ids' => 'roles del empleado',
            'role_ids.*' => 'rol seleccionado'
        ];
    }
}
