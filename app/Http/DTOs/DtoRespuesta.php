<?php

namespace App\Http\DTOs;

readonly class DtoRespuesta
{
    public function __construct(
        public ?int $idRespuesta,
        public int $idTokenEncuesta,
        public int $idPregunta,
        public ?int $idOpcion,
        public ?int $idFilaMatriz,
        public ?int $idColumnaMatriz,
        public ?int $valorTexto
    ) {}

    public static function fromRequest(Request $request)
    {
        return self::fromArray($request->all());
    }

    public static function fromArray(array $request, string $tipoPregunta): self
    {
        return new self(
            idRespuesta: $request['id_respuesta'] ?? null,
            idTokenEncuesta: $request['id_token_encuesta'],
            idPregunta: $request['id_pregunta'],
            idOpcion: $request['id_opcion'] ?? null,
            idFilaMatriz: $request['id_fila_matriz'] ?? null,
            idColumnaMatriz: $request['idColumnaMatriz'] ?? null,
            valorTexto: $request['valor_texto']
        );
    }

    public function toArray(): array
    {
        return [
            'id_respuesta' => $this->$idRespuesta,
            'id_token_encuesta' => $this->$idTokenEncuesta,
            'id_pregunta' => $this->$idPregunta,
            'id_opcion' => $this->$idOpcion ?? null,
            'id_fila_matriz' => $this->$idFilaMatriz,
            'idColumnaMatriz' => $this->$idColumnaMatriz,
            'valor_texto' => $this->$valorTexto,
        ];
    }
}
