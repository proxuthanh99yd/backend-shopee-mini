<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Classify;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(string $filter)
    {
        try {
            return match ($filter) {
                "all" => Order::orderBy('order_date', 'desc')->paginate(20),
                "waiting" => Order::where('status', 0)->paginate(20),
                "shipping" => Order::where('status', 1)->paginate(20),
                "completed" => Order::where('status', 2)->paginate(20),
                "cancelled" => Order::where('status', 3)->paginate(20),
                default => throw new Exception("Query param not match!", 500),
            };
        } catch (Exception $e) {
            throw new Exception("Get order items failed", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function show(string $id)
    {
        try {
            return Order::with('order_items', 'user')->where('id', $id)->first();
        } catch (Exception $e) {
            throw new Exception("Get order items failed", 500);
        }
    }

    /**
     * @throws Exception
     */
    public function update(string $id, string $filter)
    {
        try {
            $order = Order::with('order_items')->where('id', $id)->where('status', '!=',3)->first();
            if ($filter == 'waiting' && $order->status != 0) {
                $order->status = 0;
                foreach ($order->order_items as $item) {
                    $classify = Classify::find($item->classify_id);
                    $classify->stock = $classify->stock + $item->quantity;
                    $classify->save();
                }
            }
            if ($filter == 'shipping' && $order->status != 1 && $order->status == 0) {
                foreach ($order->order_items as $item) {
                    $classify = Classify::find($item->classify_id);
                    $classify->stock = $classify->stock - $item->quantity;
                    $classify->save();
                }
                $order->status = 1;
            }
            if ($filter == 'completed' && $order->status != 2 && $order->status == 1) {
                $order->status = 2;
            }
            $order->save();
            return $order;
        } catch (Exception $e) {
            throw new Exception("Get order items failed", 500);
        }
    }
}
