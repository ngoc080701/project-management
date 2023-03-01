<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailRegister extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("XÁC NHẬN ĐĂNG KÝ TÀI KHOẢN")
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('email_verification', [
                'name' => $this->user->last_name,
                'url' => $this->url
            ]);
    }
}
