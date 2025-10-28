<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PracticaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nivel" => "required",
            "seccion" => "required",
            "descripcion" => "required",
            "codigo" => "required",
            "lineas" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "nivel.required" => "Debes completar este campo",
            "seccion.required" => "Debes completar este campo",
            "descripcion.required" => "Debes completar este campo",
            "codigo.required" => "Debes ingresar el código fuente",
            "lineas.required" => "Debe existir al menos una línea de código",
        ];
    }
}
