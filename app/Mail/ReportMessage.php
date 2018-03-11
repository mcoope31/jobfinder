<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportMessage extends Mailable
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
    public $id;
    public $type;

    public function __construct($request, $id, $type)
    {
        $this->name = $request->name;
        if(isset($request->email)) $this->email = $request->email;
        $this->message = $request->message;
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'Job #'.$this->id.' ('.$this->name.') has been reported';
        if($this->type == 'user') $title = 'User #'.$this->id.' ('.$this->name.', '.$this->email.') has been reported';
        $content = $this->message;
        return $this->to(env('MAIL_USERNAME',''))->subject($this->name.' has been reported')->from(env('MAIL_USERNAME',''))
            ->view('emails.send',compact('title', 'content'));
    }
}
