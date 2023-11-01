<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\ClassificationGroup;
use App\Models\Classify;
use App\Models\Order;
use App\Models\Product;
use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index():JsonResponse
    {
        try {
            $classify = Classify::where("stock", 0)->get("classification_group_id");
            $classification = ClassificationGroup::whereIn("id", $classify)->get("product_id");
            $arr = [];
            for ($i = 0; $i < count($classification); $i++) {
                $arr[$i] = $classification[$i]->product_id;
            }
            return response()->json([
                'waitOrder' => Order::where('status',0)->count(),
                'shippingOrder' => Order::where('status',1)->count(),
                'completed' => Order::where('status',2)->count(),
                'cancelOrder' => Order::where('status',3)->count(),
                'activeProduct' => Product::where('active',1)->count(),
                'blockProduct' => Product::where('active',0)->count(),
                'outOfStockProduct'=>Product::whereIn('id', $arr)->count()
            ]);
        } catch (Exception $exception) {
            throw new Error("change status failed!s", 500);
        }
    }
}
