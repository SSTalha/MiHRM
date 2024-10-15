<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordSetupEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $token;
    public $setupUrl;
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        //$this->setupUrl = url('/password-setup?email=' . urlencode($user->email) . '&token=' . $token);
        $this->setupUrl = env('FRONTEND_URL').'/password-setup?email=' . urlencode($user->email) . '&token=' . $token;
    }

    public function build()
    {
        return $this->view('emails.password_setup')
                    ->subject('Set up your password')
                    ->with([
                        'user' => $this->user,
                        'setupUrl' => $this->setupUrl,
                    ]);
    }
}
