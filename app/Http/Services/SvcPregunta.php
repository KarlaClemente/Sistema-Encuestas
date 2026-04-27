<?php

namespace App\Http\Services;

use App\Http\DTOs\in\DtoColumnaMatrizIn;
use App\Http\DTOs\in\DtoFilaMatrizIn;
use App\Http\DTOs\in\DtoOpcionPreguntaIn;
use App\Http\DTOs\in\DtoPreguntaIn;
use App\Http\DTOs\out\DtoPreguntaOut;
use App\Models\Pregunta;
use Illuminate\Support\Facades\DB;

class SvcPregunta
{
    public function __construct(
        private SvcTipoPregunta $svcTipoPregunta,
        private SvcOpcionPregunta $svcOpcion,
        private SvcFilaMatriz $svcFila,
        private SvcColumnaMatriz $svcColumna,
    ){}

    /**
     * Obtiene la pregunta junto con sus opciones, filas o columnas, dependiendo del tipo de pregunta que sea
     */
    public function getById(int $id): DtoPreguntaOut
    {
        $pregunta = Pregunta::findOrFail($id);

        return DtoPreguntaOut::fromModel($pregunta);
    }

    /**
     * Valida que la pregunta pertenezca a una encuesta o plantilla
     */
    private function validateOrigen(DtoPreguntaIn $dto): void
    {
        $ambos = isset($dto->idEncuesta) && isset($dto->idEncuestaPlantilla);
        $ninguno = !isset($dto->idEncuesta) && !isset($dto->idEncuestaPlantilla);

        if ($ambos || $ninguno) {
            throw new \Exception('La pregunta debe pertenecer a una encuesta o plantilla, no a ambas ni a ninguna.');
        }
    }

    /**
     * Valida que la estructura de la pregunta corresponda al tipo de pregunta
     */
    private function validateEstructuraByTipo(DtoPreguntaIn $dto, string $nombreTipo): void
    {
        if ($nombreTipo === 'opcion multiple') {
            if (empty($dto->opciones)) {
                throw new \Exception('Las preguntas de opción multple deben tener opciones');
            }
            if ($dto->minSeleccion === 0 && $dto->maxSeleccion === 0) {
                throw new \Exception('Las preguntas de opción multiple deben tener un mínimo y máximo de opciones a seleccionar');
            }
        } elseif ($nombreTipo === 'matriz' && (empty($dto->filasMatriz) || empty($dto->columnasMatriz))) {
            throw new \Exception('Las preguntas de tipo matriz deben tener filas y columnas');
        } elseif ($nombreTipo === 'abierta' && (!empty($dto->opciones) || !empty($dto->filasMatriz) || !empty($dto->columnasMatriz) || $dto->minSeleccion > 0 || $dto->maxSeleccion > 0)) {
            throw new \Exception('Las preguntas abiertas no deben tener opciones, filas, columnas ni un mínimo o máximo de selección');
        }
    }

    /**
     * Almacena la información de la pregunta, dependiendo del tipo de pregunta que sea
     * @param DtoPreguntaIn $dto DTO con la información de la pregunta a almacenar
     * @return DtoPreguntaOut DTO con la información de la pregunta recién creada
     */
    public function store(DtoPreguntaIn $dto): DtoPreguntaOut
    {
        $this->validateOrigen($dto);
        $nombreTipo = $this->svcTipoPregunta->getNombreById($dto->idTipoPregunta);
        $this->validateEstructuraByTipo($dto, $nombreTipo);

        return DB::transaction(function () use ($dto, $nombreTipo) {
            $pregunta = Pregunta::create($dto->toArray());
            if ($nombreTipo === 'opcion multiple') {
                $this->createComponentes($dto->opciones, $pregunta->id_pregunta, $this->svcOpcion, DtoOpcionPreguntaIn::class);
            } elseif ($nombreTipo === 'matriz') {
                $this->createComponentes($dto->filasMatriz, $pregunta->id_pregunta, $this->svcFila, DtoFilaMatrizIn::class);
                $this->createComponentes($dto->columnasMatriz, $pregunta->id_pregunta, $this->svcColumna, DtoColumnaMatrizIn::class);
            }

            return DtoPreguntaOut::fromModel($pregunta);
        });
    }

    /**
     * Crea los componentes(opciones, filas o columnas) de una pregunta
     *
     * @param array $componentes Arreglo con la información de los componentes a crear
     * @param int $idPregunta id de la pregunta a la que pertenece el componente
     * @param SvcComponentePregunta $svc Servicio del componente a guardar
     * @param string $claseDto El nombre de la clase del dto del componente a guardar
     */
    private function createComponentes(array $componentes, int $idPregunta, SvcComponentePregunta $svc, string $claseDto): void
    {
        foreach ($componentes as $key => $componente) {
            $componente['id_pregunta'] = $idPregunta;
            $componente['orden'] = $key + 1;
            $svc->store($claseDto::fromArray($componente));
        }
    }

    /**
     * Elimina una pregunta específica junto con sus componentes(opciones, filas o columnas)
     *
     * @param int $id ID de la pregunta a eliminar
     * @return bool true en caso de que se haya eliminado correctamente, false en caso contrario
     */
    public function delete(int $id): bool
    {
        $pregunta = Pregunta::findOrFail($id);
        $nombreTipo = $this->svcTipoPregunta->getNombreById($pregunta->id_tipo_pregunta);

        return DB::transaction(function () use ($nombreTipo, $pregunta, $id) {
            if ($nombreTipo === 'opcion multiple') {
                $opciones = $this->svcOpcion->getByPreguntaId($id);
                $this->deleteComponentes($opciones, $this->svcOpcion);
            } elseif ($nombreTipo === 'matriz') {
                $filas = $this->svcFila->getByPreguntaId($id);
                $this->deleteComponentes($filas, $this->svcFila);
                $columnas = $this->svcColumna->getByPreguntaId($id);
                $this->deleteComponentes($columnas, $this->svcColumna);
            }
            // Se actualiza el orden de las demás preguntas de la encuesta
            if (isset($pregunta->id_encuesta)) {
                Pregunta::where('id_encuesta', $pregunta->id_encuesta)
                        ->where('orden', '>', $pregunta->orden)
                        ->decrement('orden');
            } else {
                Pregunta::where('id_encuesta_plantilla', $pregunta->id_encuesta_plantilla)
                        ->where('orden', '>', $pregunta->orden)
                        ->decrement('orden');
            }

            return $pregunta->delete();
        });
    }

    /**
     * Elimina un conjunto de componentes
     * @param array $componentes Arreglo de Dto's Out de los componentes a eliminar
     * @param SvcComponentePregunta $svc Servicio correspondiente al componente a agregar
     */
    private function deleteComponentes(array $componentes, SvcComponentePregunta $svc): void
    {
        foreach ($componentes as $componente) {
            $svc->delete($componente->id);
        }
    }

    /**
     * Actualiza la información de la pregunta
     * @param DtoPreguntaIn $dto DTO con la información de la pregunta a actualizar
     * @param int $id ID de la pregunta a actualizar
     */
    public function update(DtoPreguntaIn $dto, int $id): DtoPreguntaOut
    {
        $this->validateOrigen($dto);
        $tipo = $this->svcTipoPregunta->getById($dto->idTipoPregunta);
        $nombreTipo = $tipo->nombre;
        $this->validateEstructuraByTipo($dto, $nombreTipo);

        return DB::transaction(function () use ($dto, $id, $nombreTipo) {
            $pregunta = Pregunta::findOrFail($id);
            $anteriorTipo = $pregunta->id_tipo_pregunta;
            $pregunta->update($dto->toArray());
            if ($dto->idTipoPregunta === $anteriorTipo) {
                // Se actualiza la información de la pregunta y sus componentes
                if ($nombreTipo === 'opcion multiple') {
                    $opcionesAnteriores = $this->svcOpcion->getByPreguntaId($id);
                    $this->updateComponentes($dto->opciones, $opcionesAnteriores, 'id_opcion', $this->svcOpcion, DtoOpcionPreguntaIn::class, $pregunta->id_pregunta);
                } elseif ($nombreTipo === 'matriz') {
                    $filasAnteriores = $this->svcFila->getByPreguntaId($id);
                    $this->updateComponentes($dto->filasMatriz, $filasAnteriores, 'id_fila_matriz', $this->svcFila, DtoFilaMatrizIn::class, $pregunta->id_pregunta);
                    $columnasAnteriores = $this->svcColumna->getByPreguntaId($id);
                    $this->updateComponentes($dto->columnasMatriz, $columnasAnteriores, 'id_columna_matriz', $this->svcColumna, DtoColumnaMatrizIn::class, $pregunta->id_pregunta);
                }
            } else {
                // Se eliminan los componentes de la pregunta anterior
                $nombreAnteriorTipo = $this->svcTipoPregunta->getNombreById($anteriorTipo);
                if ($nombreAnteriorTipo === 'opcion multiple') {
                    $opciones = $this->svcOpcion->getByPreguntaId($id);
                    $this->deleteComponentes($opciones, $this->svcOpcion);
                } elseif ($nombreAnteriorTipo === 'matriz') {
                    $filas = $this->svcFila->getByPreguntaId($id);
                    $this->deleteComponentes($filas, $this->svcFila);

                    $columnas = $this->svcColumna->getByPreguntaId($id);
                    $this->deleteComponentes($columnas, $this->svcColumna);
                }
                // Se crean los nuevos componentes
                if ($nombreTipo === 'opcion multiple') {
                    $this->createComponentes($dto->opciones, $id, $this->svcOpcion, DtoOpcionPreguntaIn::class);
                } elseif ($nombreTipo === 'matriz') {
                    $this->createComponentes($dto->filasMatriz, $id, $this->svcFila, DtoFilaMatrizIn::class);

                    $this->createComponentes($dto->columnasMatriz, $id, $this->svcColumna, DtoColumnaMatrizIn::class);
                }
            }

            return DtoPreguntaOut::fromModel($pregunta->fresh());
        });
    }

    /**
     * Actualiza la información de un conjunto de componentes
     * @param array $componentesNuevos Arreglo que contiene la información de los componentes a acualizar
     * @param array $componentesAnteriores Arreglo que cocntiene la información de los componentes que tenía la pregunta anteriormente
     * @param string $nombreId Nombre asignado al id del tipo de componente
     * @param SvcComponentePregunta $svc Servicio correspondiente al tipo de los componentes a actualizar
     * @param string $claseDto Nombre del Dto correspondiente al tipo de los componentes a actualizar
     * @param int $id Id de la pregunta a la que corresponden los componentes
     */
    private function updateComponentes(array $componentesNuevos, array $componentesAnteriores, string $nombreId, SvcComponentePregunta $svc, string $claseDto, int $id): void
    {
        $componentesNuevosId = array_map(function ($componenteNuevo) use ($nombreId) {
                                    return $componenteNuevo[$nombreId]?? null;
                                }, $componentesNuevos);
        // Se eliminan los componentes que ya no se encuentran en los componentes nuevos
        foreach ($componentesAnteriores as $componente) {
            if (!in_array($componente->id, $componentesNuevosId)) {
                $svc->delete($componente->id);
            }
        }
        foreach ($componentesNuevos as $key => $componenteNuevo) {
            if (isset($componenteNuevo[$nombreId])) {
                // Se actualiza el componente existente
                $svc->update($claseDto::fromArray($componenteNuevo));
            } else {
                // Se crea el nuevo componente
                $componenteNuevo['id_pregunta'] = $id;
                $componenteNuevo['orden'] = $key + 1;
                $svc->store($claseDto::fromArray($componenteNuevo));
            }
        }
    }
}
