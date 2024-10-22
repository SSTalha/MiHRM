<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PerkRequestStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param string $status
     * @return void
     */
    public function __construct($user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->status === 'approved'
            ? 'Perk Request Approved'
            : 'Perk Request Rejected';

        return $this->view('emails.perk_request_status')
                    ->subject($subject)
                    ->with([
                        'username' => $this->user->name,
                        'status' => $this->status,
                    ]);
    }
}
