<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Classify;
use Error;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            return Cart::where('user_id', $request->user()->id)->with('product', 'classify')->get();
        } catch (Exception $th) {
            throw new Exception("failed!", 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productId = $request->input("productId");
        $classifyId = $request->input("classifyId");
        $quantity = $request->input("quantity");
        $userId = $request->user()->id;
        $cart = Cart::where('product_id', $productId)->where('classify_id', $classifyId)->where('user_id', $userId)->first();
        $classify = Classify::find($classifyId);
        if ($quantity <= $classify->stock) {
            if ($cart) {
                if ($cart->quantity + $quantity <= $classify->stock) {
                    $cart->quantity = $cart->quantity + $quantity;
                    $cart->save();
                    return Cart::with('product', 'classify')->find($cart->id);
                } else {
                    throw new Error("out of stock", 500);

                }
            }
            $newCart = new Cart();
            $newCart->quantity = $quantity;
            $newCart->user_id = $userId;
            $newCart->product_id = $productId;
            $newCart->classify_id = $classifyId;
            $newCart->save();
            return Cart::with('product', 'classify')->find($newCart->id);
        }
        throw new Error("out of stock", 500);
    }


    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(Request $request, string $id)
    {
        try {
            $quantity = $request->input("quantity");
            $userId = $request->user()->id;
            $cart = Cart::where('id', $id)->where('user_id', $userId)->first();
            $classify = Classify::find($cart->classify_id);
            if ($quantity && $quantity <= $classify->stock) {
                $cart->quantity = $quantity;
                $cart->save();
                return Cart::with('product', 'classify')->find($cart->id);
            } else {
                throw new Error("out of stock", 500);
            }
        } catch (Exception $exception) {
            throw new Exception("Update cart failed", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Exception
     */
    public function destroy(Request $request, string $id)
    {
        $userId = $request->user()->id;
        $cart = Cart::where('id', $id)->where('user_id', $userId)->first();
        if ($cart) {
            $cart->delete();
            return $cart;
        }
        throw new Exception("remove cart failed", 500);
    }
}
