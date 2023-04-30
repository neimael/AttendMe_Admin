<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;


    /**
     * Create a new message instance.
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    public function build()
    {
    return $this->view('emails.admin-created')
                    ->subject('Admin Account Created')
                    ->with([
                        'email' => $this->email,
                        'password' => $this->password
                    ]);
                }
    /**
     * Get the message envelope.
     */
    

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
   
}
