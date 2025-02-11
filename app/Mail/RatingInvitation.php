<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RatingInvitation extends Mailable
{
    use SerializesModels;

    public $meeting;

    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    public function build()
    {
        return $this->subject('Permintaan Feedback Meeting')
                    ->view('emails.rating_invitation')
                    ->with(['meeting' => $this->meeting]);
    }
}
