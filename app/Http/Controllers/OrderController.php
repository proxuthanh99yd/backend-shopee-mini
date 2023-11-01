<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Classify;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index(Request $request, string $filter)
    {
        try {
            return match ($filter) {
                "all" => Order::where('user_id', $request->user()->id)
                    ->with('order_items')
                    ->orderBy('order_date', 'desc')
                    ->get(),
                "to_wait" => Order::where('user_id', $request->user()->id)
                    ->where('status', 0)
                    ->with('order_items')
                    ->get(),
                "to_ship" => Order::where('user_id', $request->user()->id)
                    ->where('status', 1)
                    ->with('order_items')
                    ->get(),
                "completed" => Order::where('user_id', $request->user()->id)
                    ->where('status', 2)
                    ->with('order_items')
                    ->get(),
                "cancelled" => Order::where('user_id', $request->user()->id)
                    ->where('status', 3)
                    ->with('order_items')
                    ->get(),
                default => throw new Exception("Query param not match!", 500),
            };
        } catch (Exception $e) {
            throw new Exception("Get order items failed", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {
            $order = new Order();
            $order->order_date = now();
            $order->total_price = 0;
            $order->user_id = $request->user()->id;
            $order->save();
            $cart = Cart::whereIn('id', $request->input('cartId'))->with('product', 'classify')->get();
            $total_price = 0;
            $orderItems = [];
            foreach ($cart as $value) {
                $total_price += $value->quantity * ($value->classify->price - ($value->classify->price * ($value->product->discount /100)));
                array_push($orderItems, [
                    'quantity' => $value->quantity,
                    'price' => $value->classify->price,
                    'product_id' => $value->product->id,
                    'order_id' => $order->id,
                    'classify_id' => $value->classify->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $order->total_price = $total_price;
            $order->save();
            OrderItem::insert($orderItems);
            Cart::whereIn('id', $request->input('cartId'))->delete();
            return OrderItem::where('order_id', $order->id)->with('product', 'classify')->get();
        } catch (Exception $ex) {
            throw new Exception("create order failed!", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function cancel(string $id)
    {
        try {
            $order = Order::with('order_items')->where('id', $id)->first();
            if ($order->status == 0) {
                $order->status = 3;
                $order->save();
                return $order;
            }
            throw new Exception("Cancel order items failed", 500);
        } catch (Exception $e) {
            throw new Exception("Cancel order items failed", 500);
        }
    }
}
