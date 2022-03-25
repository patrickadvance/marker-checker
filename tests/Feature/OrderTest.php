<?php

namespace Tests\Feature;

use App\Events\OrderSubmitted;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Notifications\NewOrderSubmitted;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature that an admin can submit an order request and notify other administrators.
     *
     * @return void
     */
    public function test_an_admin_can_submit_an_order_request_and_notify_other_administrators()
    {
        Notification::fake();

        $admin_user = User::role('admin')->first();

        Sanctum::actingAs($admin_user);

        $changes = [
            "first_name" => $this->faker->name(),
            "last_name" => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];

        $response = $this->post(route('orders.index'),[
            'type' => Order::CREATE_TYPE,
            'changes' => $changes,
        ]);

        $this->assertDatabaseHas('orders', [
            'type' => Order::CREATE_TYPE,
            'changes' => json_encode($changes)
        ]);

        // This assert other administrators will be notified of the order request.
        $admin_user_two = User::role('admin')->findOrFail(2);

        Notification::assertSentTo(
            $admin_user_two,
            NewOrderSubmitted::class
        );

        $response->assertStatus(201);
    }

    /**
     * A basic feature that an admin can approve a pending order.
     *
     * @return void
     */
    public function test_an_admin_can_approve_a_pending_order()
    {
        $admin_user_one = User::role('admin')->first();

        $admin_user_two = User::role('admin')->find(2);

        Sanctum::actingAs($admin_user_one);

        $order = Order::factory()->create([
            'sent_by' => $admin_user_two->id
        ]);

        $response = $this->get(route('orders.approve',[
            'order' => $order->id
        ]));

        $this->assertModelExists(User::whereEmail($order->changes['email'])->first());

        $response->assertOk(200);
    }

    /**
     * A basic feature that an admin can decline a pending order.
     *
     * @return void
     */
    public function test_an_admin_can_decline_a_pending_order()
    {
        $admin_user_one = User::role('admin')->first();

        $admin_user_two = User::role('admin')->find(2);

        Sanctum::actingAs($admin_user_one);

        $order = Order::factory()->create([
            'sent_by' => $admin_user_two->id
        ]);

        $response = $this->get(route('orders.decline',[
            'order' => $order->id
        ]));

        $this->assertSoftDeleted($order);

        $response->assertOk(200);
    }
}
