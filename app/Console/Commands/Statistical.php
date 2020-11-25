<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Movie\MovieRepositoryInterface;
use Mail;

class Statistical extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:statistical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count new user and new movie in month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $user, MovieRepositoryInterface $movie)
    {
        parent::__construct();
        $this->user = $user;
        $this->movie = $movie;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = $this->user->getNewUserInMonth();
        $movies = $this->movie->getNewMovieInMonth();

        $data = [
            'users' => $users,
            'movies' => $movies,
            'countUser' => $users->count,
            'countMovie' => $movies->count,
        ];
        $user = config('config.admin_email');
        Mail::send('backend.mails.statistical_month', $data, function ($message) use ($user) {
            $message->from(config('config.contact_email'), config('config.contact_name'));
            $message->to(config('config.admin_email'), config('config.contact_name'));
            $message->subject(trans('statistic_month'));
        });
    }
}
