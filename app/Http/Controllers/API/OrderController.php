<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return OrderResource::collection(Order::paginate());
    }

    /**
     * Display the specified order.
     *
     * @return OrderResource
     */
    public function show(Order $order)
    {
        return OrderResource::make($order);
    }
}
