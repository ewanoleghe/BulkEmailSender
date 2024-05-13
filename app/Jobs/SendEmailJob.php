<?php

namespace App\Jobs;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $mailData;
    protected $replyTo;
    protected $senderName;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param array $mailData
     * @param string|null $replyTo
     * @param string|null $senderName
     */
    public function __construct($email, $mailData, $replyTo = null, $senderName = null)
    {
        $this->email = $email;
        $this->mailData = $mailData;
        $this->replyTo = $replyTo;
        $this->senderName = $senderName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mail = new SendMail($this->mailData);

        if ($this->replyTo !== null) {
            $mail->replyTo($this->replyTo);
        }

        if ($this->senderName !== null) {
            $mail->from($this->email, $this->senderName);
        }

        Mail::to($this->email)->send($mail);
    }
}
