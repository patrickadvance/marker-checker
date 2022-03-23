<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\AssignRole;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:role {user} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns a role to the user';

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
        $bar = $this->output->createProgressBar(2);
        $bar->start();

        $this->line('');
        $this->info('Locating the specified user');

        $this->line('');
        $user = User::findOrFail($this->argument('user'));

        $this->line('');
        $this->info('Getting the specified role');

        $this->line('');
        $role = Role::where('name', $this->argument('role'))->firstOrFail();

        $this->line('');
        $bar->advance();

        $this->line('');
        $this->info('User and role has been found');

        $this->line('');
        $this->info('Now trying to assign the specified role to user');

        $this->line('');
        AssignRole::dispatchSync(
            $user,
            $role
        );

        $this->line('');
        $bar->advance();

        $this->line('');
        $this->info('Role has been assign to the user');

        $this->line('');
        $bar->finish();
    }
}
