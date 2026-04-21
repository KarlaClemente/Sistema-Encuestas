<?php

namespace App\Http\Services;

use App\Http\DTOs\in\DtoComponentePreguntaIn;

interface SvcComponentePregunta
{
    public function getByPreguntaId(int $idPregunta): array;

    public function store(DtoComponentePreguntaIn $in);

    public function update(DtoComponentePreguntaIn $in);

    public function delete(int $id): bool;
}
