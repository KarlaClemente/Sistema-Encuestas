<?php

namespace App\Http\DTOs\in;

use Illuminate\Http\Request;

final readonly class DtoPreguntaIn
{
    public function __construct(
        public ?int $idPregunta,
        public string $texto,
        public int $orden,
        public int $idTipoPregunta,
        public ?int $idEncuestaPlantilla,
        public ?int $idEncuesta,
        public array $opciones = [],
        public array $filasMatriz = [],
        public array $columnasMatriz = [],
        public int $minSeleccion = 0,
        public int $maxSeleccion = 0,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $arr): self
    {
        return new self(
            idPregunta: isset($arr['id_pregunta']) ? (int) $arr['id_pregunta'] : null,
            texto: $arr['texto'],
            orden: (int) $arr['orden'],
            idTipoPregunta: (int) $arr['id_tipo_pregunta'],
            idEncuestaPlantilla: isset($arr['id_encuesta_plantilla']) ? (int) $arr['id_encuesta_plantilla'] : null,
            idEncuesta: isset($arr['id_encuesta']) ? (int) $arr['id_encuesta'] : null,
            opciones: isset($arr['opciones']) ? $arr['opciones'] : [],
            filasMatriz: isset($arr['filas']) ? $arr['filas'] : [],
            columnasMatriz: isset($arr['columnas']) ? $arr['columnas'] : [],
            minSeleccion : (int) ($arr['min_seleccion'] ?? 0),
            maxSeleccion: (int) ($arr['max_seleccion'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'id_pregunta' => $this->idPregunta,
            'texto' => $this->texto,
            'orden' => $this->orden,
            'id_tipo_pregunta' => $this->idTipoPregunta,
            'id_encuesta_plantilla' => $this->idEncuestaPlantilla,
            'id_encuesta' => $this->idEncuesta,
            'opciones' => $this->opciones,
            'filas_matriz' => $this->filasMatriz,
            'columnas_matriz' => $this->columnasMatriz,
            'min_seleccion' => $this->minSeleccion,
            'max_seleccion' => $this->maxSeleccion,
        ];
    }
}
