<?php

namespace App\Jobs;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this -> user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $actionURL = url(env('FRONT_END_URL', 'http://localhost:5173') . '/auth/verify-email/' . $this->user->id . '/' . $this->user->email_verification_hash);
        try {
            Mail::to($this->user->email)->send(new VerifyEmail($actionURL));

        } catch (\Exception $e) {
            \Log::error('Error sending email verify: ' . $e->getMessage());
        }
    }
}