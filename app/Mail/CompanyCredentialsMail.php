<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $password;
    public $isReset;

    /**
     * Create a new message instance.
     */
    public function __construct($company, $password, $isReset = false)
    {
        $this->company = $company;
        $this->password = $password;
        $this->isReset = $isReset;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->isReset 
            ? 'Your Company Account Password Has Been Reset'
            : 'Welcome! Your Company Account Credentials';
            
        return $this->subject($subject)
                    ->view('emails.company_credentials')
                    ->with([
                        'company' => $this->company,
                        'password' => $this->password,
                        'isReset' => $this->isReset
                    ]);
    }
}