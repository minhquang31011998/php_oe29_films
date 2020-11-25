<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendStatisticalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $countUser;
    protected $countMovie;

    public function __construct($countUser, $countMovie)
    {
        $this->countUser = $countUser;
        $this->countMovie = $countMovie;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'countUser' => $this->countUser,
            'countMovie' => $this->countMovie,
        ];
        $user = config('config.admin_email');
        Mail::send('backend.mails.statistical_month', $data, function ($message) use ($user) {
            $message->from(config('config.contact_email'), config('config.contact_name'));
            $message->to(config('config.admin_email'), config('config.contact_name'));
            $message->subject(trans('statistic_month'));
        });
    }
}
