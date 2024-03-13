<?php

namespace App\Jobs;
use App\Mail\UserWelcomeMail;
use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class sendUserRegisterMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     */
    public function __construct($details)
    {
        //
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        sleep(5);
        logger('email sent!');
        $email = new UserWelcomeMail($this->details);
        Mail::to($this->details['email'])->send($email);
    }
}
