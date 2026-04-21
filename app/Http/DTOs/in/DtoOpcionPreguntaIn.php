<?php

namespace App\Http\DTOs\in;

readonly class DtoOpcionPreguntaIn extends DtoComponentePreguntaIn
{
    public static function fromArray(array $arr): self
    {
        return new self(
            id: isset($arr['id_opcion']) ? (int) $arr['id_opcion'] : null,
            idPregunta: (int) $arr['id_pregunta'],
            texto: $arr['texto'],
            orden: (int) $arr['orden'],
        );
    }

    protected function getIdNombre(): string
    {
        return 'id_opcion';
    }
}
