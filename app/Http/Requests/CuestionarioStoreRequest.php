<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuestionarioStoreRequest extends FormRequest
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
            "pregunta" => "required",
            "resp1" => "required",
            "resp2" => "required",
            "resp3" => "required",
            "resp4" => "required",
            "correcta" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "seccion.required" => "Debes completar este campo",
            "pregunta.required" => "Debes completar este campo",
            "resp1.required" => "Debes completar este campo",
            "resp2.required" => "Debes completar este campo",
            "resp3.required" => "Debes completar este campo",
            "resp4.required" => "Debes completar este campo",
            "correcta.required" => "Debes completar este campo",
        ];
    }
}
