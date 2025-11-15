<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Confirmation: Your Message to Happinest Animal Shelter';
        
        return $this->from('noreply@happinest.org', 'Happinest Animal Shelter')
                    ->subject($subject)
                    ->view('emails.contact-confirmation')
                    ->with([
                        'data' => $this->contactData,
                    ]);
    }
}