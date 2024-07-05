<?php

namespace App\Jobs;

use App\Mail\LearningTimeReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class SendLearningTimeReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $message)
    {
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new LearningTimeReminder($this->message));
    }
}
