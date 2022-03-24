<?php

namespace App\Http\Controllers;

use App\Events\OrderApproved;
use App\Events\OrderDeclined;
use App\Events\OrderSubmitted;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::whereStatus(Order::PENDING_STATUS)->paginate(100);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        // $order = $request->user()->orders()->create($request->only(['user_id','type','changes']));

        $order = $request->user()->orders()->create([
            'type' => $request->input('type'),
            'user_id' => $request->input('user_id'),
            'changes' => $request->input('changes'),
            'status' => Order::PENDING_STATUS
        ]);

        event(new OrderSubmitted($order));

        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order;
    }

    /**
     * Approve the order sent.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function approve(Order $order)
    {
        if($order->status == Order::PENDING_STATUS){
            $order->status = Order::APPROVED_STATUS;
            $order->authorized_by = auth()->id();

            $order->save();

            event(new OrderApproved($order));
        }

        return $order->fresh();
    }

    /**
     * Decline the order sent.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function decline(Order $order)
    {
        if($order->status == Order::PENDING_STATUS ){
            $order->status = Order::DECLINED_STATUS;
            $order->authorized_by = auth()->id();

            $order->save();

            event(new OrderDeclined($order));
        }

        return new JsonResponse(['message' => 'Order Declined Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return new JsonResponse(['message' => 'Order Deleted Successfully']);
    }
}
