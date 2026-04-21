<?php

namespace App\Http\DTOs\in;

readonly class DtoColumnaMatrizIn extends DtoComponentePreguntaIn
{
    public static function fromArray(array $arr): self
    {
        return new self(
            id: isset($arr['id_columna_matriz']) ? (int) $arr['id_columna_matriz'] : null,
            idPregunta: (int) $arr['id_pregunta'],
            texto: $arr['texto'],
            orden: (int) $arr['orden'],
        );
    }

    protected function getIdNombre(): string
    {
        return 'id_columna_matriz';
    }
}
