<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EncuestaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_grupo' => 'required|integer|exists:grupo,id_grupo',
            'id_tipo_encuesta' => 'required_without:id_plantilla|integer|exists:tipo_encuesta,id_tipo_encuesta',
            'id_plantilla' => 'nullable|integer|exists:encuesta_plantilla,id_encuesta_plantilla',
            'titulo' => 'required_without:id_plantilla|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date|after:fecha_inicio',
            'texto_advertencia' => 'nullable|string',
            'estilo' => 'required_without:id_plantilla|string|max:100'
        ];
    }
}
