<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTokenResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $release;
    public $of;
    public $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($release)
    {
        $this->release = $release;

        $this->subject = "Recuperación de contraseña";
        $this->of ="support@charlizespa.com";
        $this->name = "Support";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->of,$this->name)
        ->view('emails.message-resetpassword');
        
    }
}
