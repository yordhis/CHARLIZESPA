<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ApplicantsRecieved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     *   La @var release
     *   Es el mensaje que envia el usaurio (comunicado o solicitud)
     *   para formar parte del equipo del Espa 
     */
    public $subject;
    public $release;
    public $of;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($release)
    {
        $this->release = $release;
        $this->subject = $release->subject ?? null;
        $this->of = $release->of ?? null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->release->type == 1){
            return $this->from($this->of,$this->release->name)
                        ->view('emails.message-support');
        }else{
            return $this->from($this->of,$this->release->name)
                    ->view('emails.message-applicants');
        } 
   }
}
