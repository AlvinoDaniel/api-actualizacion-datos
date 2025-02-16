<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class RecoveryPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $persona;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $persona)
    {
        $this->token = $token;
        $this->persona = $persona;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset_password');
    }

     /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
          from: new Address('mediconsult@example.com', 'MEDICONSULT'),
          subject: 'Clientes en riesgo por consumo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.finanzas.alerta_clientes',
        );
    }
}
