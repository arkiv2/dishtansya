<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CreateOrderData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->product_id = $event->product->id;
        $order->quantity = $event->quantity;
        $order->save();
    }
}
