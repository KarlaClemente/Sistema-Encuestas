<?php

namespace App\Http\Services;

use App\Http\DTOs\out\DtoTipoEncuestaOut;
use App\Models\TipoEncuesta;

class SvcTipoEncuesta
{
    public function index(): array
    {
        $dtos = TipoEncuesta::all()
            ->map(function ($tipo) {
                return DtoTipoEncuestaOut::fromModel($tipo);
            });

        return $dtos->toArray();
    }
}
