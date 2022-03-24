<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateUserFromOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Order request to act on.
     */
    public $order;

    /**
     * Create a new job instance for an Order.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Create a new user in the database.
     *
     * @return void
     */
    public function handle()
    {
        $user = new User();
        $user->first_name = $this->order->changes->first_name;
        $user->last_name = $this->order->changes->last_name;
        $user->email = $this->order->changes->email;
        $user->password = bcrypt('password');
        $user->save();
    }
}
