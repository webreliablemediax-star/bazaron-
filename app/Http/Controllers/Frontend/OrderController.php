<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        // Product find karo
        $product = Product::find($request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // Order create karo
        $order = new Order();
        $order->product_id = $product->id;
        $order->user_id = auth()->user()->id; // customer id
        $order->vendor_id = $product->vendor_id; // vendor id from product
        $order->status = 'pending';
        $order->save();

        return redirect()->back()->with('success', 'Order placed successfully');
    }
}
