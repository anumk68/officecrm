<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quote;
    public $pdfContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote, $pdfContent)
    {
        $this->quote = $quote;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Quote from DigiRush')
            ->markdown('emails.quote')
            ->attachData($this->pdfContent, 'Digirush-quote.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
