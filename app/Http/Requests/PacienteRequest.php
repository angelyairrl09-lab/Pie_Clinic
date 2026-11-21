<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',

            'apellido_paterno' => 'required|string|max:100',

            'apellido_materno' => 'nullable|string|max:100',

            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            'sexo' => [
                'required',
                'in:MASCULINO,FEMENINO,OTRO,NO_ESPECIFICADO'
            ],

            'telefono' => 'nullable|string|max:20',

            'correo' => 'nullable|email|max:150',

            'calle' => 'nullable|string|max:200',

            'numero_exterior' => 'nullable|string|max:20',

            'numero_interior' => 'nullable|string|max:20',

            'colonia' => 'nullable|string|max:100',

            'municipio' => 'nullable|string|max:100',

            'estado' => 'nullable|string|max:100',

            'codigo_postal' => 'nullable|digits:5',

            'ocupacion' => 'nullable|string|max:100',

            'contacto_emergencia' => 'nullable|string|max:200',

            'telefono_emergencia' => 'nullable|string|max:20',

            'parentesco_emergencia' => 'nullable|string|max:50',

            'observaciones' => 'nullable|string|max:5000'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' =>
                'Ingresa el nombre del paciente.',

            'apellido_paterno.required' =>
                'Ingresa el apellido paterno.',

            'fecha_nacimiento.required' =>
                'Ingresa la fecha de nacimiento.',

            'fecha_nacimiento.before_or_equal' =>
                'La fecha de nacimiento no puede ser futura.',

            'sexo.required' =>
                'Selecciona una opción.',

            'sexo.in' =>
                'La opción seleccionada no es válida.',

            'correo.email' =>
                'Ingresa un correo electrónico válido.',

            'codigo_postal.digits' =>
                'El código postal debe contener 5 dígitos.'
        ];
    }
}
