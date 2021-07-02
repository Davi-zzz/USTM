<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailBoleto extends Mailable
{
    use Queueable, SerializesModels;


    private $user;
    private $historico_compras;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($historico_compras)
    {
        $this->historico_compras = $historico_compras;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmação de compra via boleto.')
                ->view('emails.boleto', compact('historico_compras'))
                ->with([
                    'historico' => $this->historico_compras,
                ]);

    }
}
