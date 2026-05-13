<?php

namespace App\Http\Services;

use App\Http\DTOs\DtoPlantilla;
use App\Http\DTOs\out\DtoCorreoOut;
use App\Models\Plantilla;
use Illuminate\Support\Facades\DB;

class SvcPlantilla
{
    public function __construct(
        private SvcCorreo $svcCorreo,
    ){}

    /**
     * Obtiene todas las plantillas de una encuesta
     * @param int $idEncuesta El id de la encuesta de la que se quiere recuperar todas sus plantillas
     * @param DtoPlantilla[] Un arreglo de DTOs que contienen la información de las plantillas de correos
     */
    public function getPlantillasByIdEncuesta(int $idEncuesta): array
    {
        $plantillas = Plantilla::where('id_encuesta', $idEncuesta)
                    ->get()
                    ->map(fn ($plantilla) => DtoPlantilla::fromModel($plantilla));
        return $plantillas->toArray();
    }

    /**
     * Obtiene las plantillas de una encuesta y de un tipo específico
     * @param int @idEncuesta Id de la encuesta a obtener las plantillas
     * @param string $tipo Tipo de plantilla a obtener(invitacion, recordatorio o completado)
     */
    public function getPlantillaByTipoByIdEncuesta(int $idEncuesta, string $tipo): DtoPlantilla
    {
        $plantilla = Plantilla::where('id_encuesta', $idEncuesta)
                    ->where('tipo', $tipo)
                    ->first();
        return DtoPlantilla::fromModel($plantilla);
    }

    public function getCorreos(int $id): array
    {
        $plantilla = Plantilla::with('correos')->findOrFail($id);
        return $plantilla->correos
            ->map(fn ($correo) => DtoCorreoOut::fromModel($correo))
            ->toArray();
    }

    /**
     * Crea 3 correos bases para una encuesta o encuesta plantilla
     * @param int $id El id de la encuesta o encuesta plantilla a la que se le va a crear sus correos base
     * @param bool $esEncuesta True en caso de que sea una encuesta a la que se va a crea los correos base, False en caso contrario
     * @param DtoPlantilla[] Arreglo con los DTO's de los correos base que se crearon
     */
    public function createBasePlantilla(int $id, bool $esEncuesta): array
    {
        $plantillaInvitacion = [
            'tipo' => 'invitacion',
            'asunto' => 'Invitación para la realización de encuesta',
            'cuerpo' => '{{nombre_participante}} se le invita a contestar la encuesta {{titulo_encuesta}} la cual inicia el {{fecha_inicio}} y finaliza el {{fecha_termino}}
            Acceda al siguiente enlace
            {{enlace_encuesta}}',
        ];
        $plantillaRecordatorio = [
            'tipo' => 'recordatorio',
            'asunto' => 'Recordatorio para la realización de encuesta',
            'cuerpo' => '{{nombre_participante}} recuerda contestar la encuesta {{titulo_encuesta}}, la cual cierra el {{fecha_termino}}
            Accede a la encuesta con el siguiente enlace
            {{enlace_encuesta}}',
        ];
        $plantillaCompletado = [
            'tipo' => 'completado',
            'asunto' => 'Recordatorio para la realización de encuesta',
            'cuerpo' => '{{nombre_participante}} se envio exitosamente tus respuestas para la envuesta {{titulo_encuesta}}',
        ];

        if ($esEncuesta) {
            $plantillaInvitacion['id_encuesta'] = $id;
            $plantillaRecordatorio['id_encuesta'] = $id;
            $plantillaCompletado['id_encuesta'] = $id;
        } else {
            $plantillaInvitacion['id_encuesta_plantilla'] = $id;
            $plantillaRecordatorio['id_encuesta_plantilla'] = $id;
            $plantillaCompletado['id_encuesta_plantilla'] = $id;
        }
        return DB::transaction(function () use ($plantillaInvitacion, $plantillaRecordatorio, $plantillaCompletado) {
            $plantillasBase[] = DtoPlantilla::fromModel(Plantilla::create($plantillaInvitacion));
            $plantillasBase[] = DtoPlantilla::fromModel(Plantilla::create($plantillaRecordatorio));
            $plantillasBase[] = DtoPlantilla::fromModel(Plantilla::create($plantillaCompletado));
            return $plantillasBase;
        });
    }

    /**
     * Actualiza el asunto y cuerpo de una plantilla
     * @param DtoPlantilla $dto Dto con los nuevos datos de la plantilla
     * @param int $id El id de la plantilla a modificar
     * @return DtoPlantilla con los nuevos datos de la plantilla.
     */
    public function update(DtoPlantilla $dto, int $id): DtoPlantilla
    {
        $plantilla = Plantilla::findOrFail($id);
        $plantilla->update($dto->toUpdateArray());

        return DtoPlantilla::fromModel($plantilla);
    }

    /**
     * Elimina de la base de datos una plantilla
     * @param int $id El id de la plantilla a eliminar
     * @return bool True en caso de haber eliminado la plantilla, False en caso contrario
     */
    public function delete(int $id): bool
    {
        $plantilla = Plantilla::with('correos')->findOrFail($id);
        $correos = $plantilla->correos;
        foreach ($correos as $correo) {
            $this->svcCorreo->delete($correo->id_correo);
        }

        return $plantilla->delete();
    }
}