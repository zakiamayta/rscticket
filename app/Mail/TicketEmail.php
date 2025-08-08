<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $pdfPath;

    public function __construct($transaction, $pdfPath = null)
    {
        $this->transaction = $transaction;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $email = $this->subject('Tiket Anda - ' . $this->transaction->event_name)
            ->view('emails.ticket-html');

        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as' => 'Tiket-' . $this->transaction->id . '.pdf',
                'mime' => 'application/pdf'
            ]);
        }

        return $email;
    }
}
