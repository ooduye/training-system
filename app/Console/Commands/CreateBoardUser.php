<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateBoardUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-user:board {email=a@a.com} {password=boarduser123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default board user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::where('email', $this->argument('email'))->delete();

        User::create([
            'email' => $this->argument('email'),
            'password' => bcrypt($this->argument('password')),
            'name' => 'Board User',
            'username' => 'boarduser',
            'profile' => User::ROLE_BOARD
        ]);

        return 1;
    }
}
