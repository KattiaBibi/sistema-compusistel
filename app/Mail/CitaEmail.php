<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CitaEmail extends Mailable
{
  use Queueable, SerializesModels;

  protected $cita, $asistente, $tipoAsunto;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($cita, $asistente, $tipoAsunto)
  {
    $this->cita = $cita;
    $this->asistente = $asistente;
    $this->tipoAsunto = $tipoAsunto;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $subject = "";
    if ($this->tipoAsunto === 'INVITACION') {
      $subject = "馃憠 Invitaci贸n a la reuni贸n " . strtoupper($this->cita->tipo) . " " . $this->cita->titulo;
    } elseif ($this->tipoAsunto === 'REPROGRAMACION') {
      $subject = "馃憠 Reprogramaci贸n de la reuni贸n " . strtoupper($this->cita->tipo) . " " . $this->cita->titulo;
    } else {
      $subject = "馃憠 Se te elimin贸 de la reuni贸n " . strtoupper($this->cita->tipo) . " " . $this->cita->titulo;
    }

    return $this->view('mails.cita')
      ->subject($subject)
      ->with([
        'cita' => $this->cita,
        'asistente' => $this->asistente,
        'tipoAsunto' => $this->tipoAsunto
      ]);
  }
}
