<?php
namespace App\Http\DTOs\in;


abstract readonly class DtoComponentePreguntaIn
{
    public function __construct(
        public ?int $id,
        public int $idPregunta,
        public string $texto,
        public int $orden,
    ) {}

    abstract protected function getIdNombre(): string;

    abstract public static function fromArray(array $arr): self;

    public function toArray(): array
    {
        return [
            $this->getIdNombre() => $this->id,
            'id_pregunta' => $this->idPregunta,
            'texto' => $this->texto,
            'orden' => $this->orden
        ];
    }
}