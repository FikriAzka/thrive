<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class MeetingInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting;
    public $googleMeetLink;

    /**
     * Create a new message instance.
     */
    public function __construct(Meeting $meeting, $googleMeetLink)
    {
        $this->meeting = $meeting;
        $this->googleMeetLink = $googleMeetLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Rapat : ' . $this->meeting['nama_rapat'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting_invitation',
            with: [
                'meeting' => $this->meeting,
                'googleMeetLink' => $this->googleMeetLink,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
