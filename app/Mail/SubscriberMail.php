<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public string $title;
    public string $content;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->content = $message;
    }

    public function build()
    {
        return $this->subject($this->title)
                    ->markdown('emails.subscriber', [
                        'title' => $this->title,
                        'message' => $this->content
                    ]);
    }
}
