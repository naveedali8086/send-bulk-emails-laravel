<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
        $this->body = $this->email->body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // getting attachments paths and their names
        $attachments = json_decode($this->email->attachments);

        foreach ($attachments as $attachment) {
            // adding attachments as raw data in email
            $this->attachData(Storage::get($attachment->file_path), $attachment->file_name);
        }

        return $this->markdown('emails.send')
            ->to($this->email->recipient)
            ->subject($this->email->subject);
    }
}
