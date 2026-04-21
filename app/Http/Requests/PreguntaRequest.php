<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PreguntaRequest extends FormRequest
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
            'id_encuesta' => 'required_without:id_encuesta_plantilla|nullable|integer|exists:encuesta,id_encuesta',
            'id_encuesta_plantilla' => 'required_without:id_encuesta|nullable|integer|exists:encuesta_plantilla,id_encuesta_plantilla',
            'id_tipo_pregunta' => 'required|integer|exists:tipo_pregunta,id_tipo_pregunta',
            'texto' => 'required|string|max:1000',
            'orden' => 'required|integer|min:1',
            // Verificaciones de las opciones
            'opciones' => 'nullable|array',
            'opciones.*.id_opcion*' => 'nullable|integer|exists:opcion_pregunta,id_opcion',
            'opciones.*.id_pregunta*' => 'nullable|integer|exists:pregunta,id_pregunta',
            'opciones.*.orden*' => 'nullable|integer|min:1',
            'opciones.*.texto*' => 'required|string|max:100',
            // Verificaciones de las filas
            'filas' => 'nullable|array',
            'filas.*.id_fila_matriz*' => 'nullable|integer|exists:fila_matriz,id_fila_matriz',
            'filas.*.id_pregunta*' => 'nullable|integer|exists:pregunta,id_pregunta',
            'filas.*.orden*' => 'nullable|integer|min:1',
            'filas.*.texto*' => 'required|string|max:100',
            // Verificaciones de las filas
            'columnas' => 'nullable|array',
            'columnas.*.id_fila_matriz*' => 'nullable|integer|exists:columna_matriz,id_columna_matriz',
            'columnas.*.id_pregunta*' => 'nullable|integer|exists:pregunta,id_pregunta',
            'columnas.*.orden*' => 'nullable|integer|min:1',
            'columnas.*.texto*' => 'required|string|max:100',

            'min_seleccion' => 'nullable|integer|min:1',
            'max_seleccion' => 'nullable|integer|min:1|gte:min_seleccion',
        ];
    }
}
