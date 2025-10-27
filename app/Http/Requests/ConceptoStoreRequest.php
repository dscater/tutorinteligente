<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConceptoStoreRequest extends FormRequest
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
            "seccion" => "required",
            "titulo" => "required",
            "descripcion" => "required",
            "url" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "seccion.required" => "Debes completar este campo",
            "titulo.required" => "Debes completar este campo",
            "descripcion.required" => "Debes completar este campo",
            "url.required" => "Debes completar este campo",
        ];
    }
}
