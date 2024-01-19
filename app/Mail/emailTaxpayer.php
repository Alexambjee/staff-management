<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class emailTaxpayer extends Mailable
{
    use Queueable, SerializesModels;
    public $maildata;

   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $email = $this->markdown('emails.emailTaxpayer')
        -> from('DTD@KRA.GO.KE')
        ->subject($this->maildata['subject'])
        ->attachData($this->maildata['attached_file'], $this->maildata['tpin'].'.pdf',['mime' => 'application/pdf']);

        $attachment = null;
        if (isset($this->maildata['filename']) && file_exists($this->maildata['filename'])) {
            $attachment = $this->maildata['filename'];
            $email->attachData($attachment, $this->maildata['tpin']."-Attached".'.pdf',['mime' => 'application/pdf']);
        }

        return $email->with('maildata', $this->maildata);
    }
}
