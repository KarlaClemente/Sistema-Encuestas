<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Http\DTOs\in\DtoCorreoIn;
use Carbon\Carbon;

class CorreoEncuesta extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public DtoCorreoIn $dto,
        public string $nombreParticipante,
        public string $tituloEncuesta,
        public Carbon $fechaInicio,
        public Carbon $fechaTermino,
        public string $enlaceEncuesta,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replaceVariables($this->dto->plantilla->asunto),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'layouts.app.Correo.correo',
            with: [
                'cuerpo' => $this->replaceVariables($this->dto->plantilla->cuerpo),
                'url' => $this->enlaceEncuesta
            ],
        );
    }

    /**
     * Reemplaza las variables disponibles en un correo por datos específicos de cada participante y encuesta
     * @param string $texto El texto a reemplazar las variables
     * @return string Texto que contiene los datos específicos del participante y la encuesta
     */
    private function replaceVariables(string $texto): string
    {
        return str_replace(['{{nombre_participante}}', '{{titulo_encuesta}}', '{{enlace_encuesta}}', '{{fecha_inicio}}', '{{fecha_termino}}'], 
                            [$this->nombreParticipante, $this->tituloEncuesta, $this->enlaceEncuesta, $this->fechaInicio->format('d/m/Y H:i'), $this->fechaTermino->format('d/m/Y H:i')],
                            $texto);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
