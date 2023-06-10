<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AffectationModified extends Mailable
{
    use Queueable, SerializesModels;

    public $checkin;
    public $checkout;
    public $startdate;
    public $enddate;
    public $area;


    /**
     * Create a new message instance.
     */
    public function __construct($checkin, $checkout, $startdate, $enddate, $area)
    {
        $this->checkin = $checkin;
        $this->checkout = $checkout;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->area = $area;
    }
   
    public function build()
    {
    return $this->view('emails.affectation-modified')
                    ->subject('Affectation Modified')
                    ->with([
                        'checkin' => $this->checkin,
                        'checkout' => $this->checkout,
                        'startdate' => $this->startdate,
                        'enddate' => $this->enddate,
                        'area' => $this->area

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
