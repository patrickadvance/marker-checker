<?php

namespace App\Subscribers;

use App\Models\User;
use App\Models\Order;
use App\Events\OrderApproved;
use App\Events\OrderDeclined;
use App\Events\OrderSubmitted;
use App\Jobs\CreateUserFromOrder;
use App\Jobs\DeleteUserFromOrder;
use App\Jobs\UpdateUserFromOrder;
use App\Notifications\NewOrderSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderEventSubscriber implements ShouldQueue
{
    use InteractsWithQueue;



    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        return [
            'App\Events\OrderSubmitted' => [
                [OrderEventSubscriber::class,'handleOrderSubmitted'],
            ],

            'App\Events\OrderApproved' => [
                [OrderEventSubscriber::class,'handleOrderApproved'],
            ],

            'App\Events\OrderDeclined' => [
                [OrderEventSubscriber::class,'handleOrderDeclined'],
            ],
        ];
    }

    /**
     * Notifies admin members of a new order request.
     *
     * @param  OrderSubmitted  $event
     * @return void
     */
    public function handleOrderSubmitted(OrderSubmitted $event)
    {
        $admin_users = User::role('admin')->get();

        foreach ($admin_users as $admin) {

            $admin->notify(New NewOrderSubmitted());
        }
    }

    /**
     * Performs a job based on an approved order type.
     *
     * @param  OrderApproved  $event
     * @return void
     */
    public function handleOrderApproved(OrderApproved $event)
    {
        $order = Order::findOrFail($event->order->id);

        if($order->type == Order::CREATE_TYPE){

            CreateUserFromOrder::dispatchSync($order);
        };

        if($order->type == Order::UPDATE_TYPE){

            UpdateUserFromOrder::dispatchSync($order);
        };

        if($order->type == Order::DELETE_TYPE){

            DeleteUserFromOrder::dispatchSync($order);
        };
    }

    /**
     * Deletes a declined order request.
     *
     * @param  OrderDeclined  $event
     * @return void
     */
    public function handleOrderDeclined(OrderDeclined $event)
    {
        $order = Order::findOrFail($event->order->id);

        $order->delete();
    }
}
