<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $name;
    public $email;
    public $message;

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->email = $request->email;
        $this->message = $request->message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'From '.$this->name.' '.$this->email;
        $content = $this->message;

        //return $this->to($_ENV['MAIL_USERNAME'])->subject("Message from ".$this->name)
            //->from($this->email)->view('emails.send',compact('title', 'content'));
        
        return $this->to(env('MAIL_USERNAME','jobfinderCSC396@gmail.com'))->subject("Message from ".$this->name)
            ->from(env('MAIL_USERNAME','jobfinderCSC396@gmail.com'))->view('emails.send',compact('title', 'content'));
    }
}
