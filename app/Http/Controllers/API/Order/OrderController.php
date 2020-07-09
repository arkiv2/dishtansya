<?php

namespace App\Http\Controllers\API\Order;

use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $order = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::where('id', $order['product_id'])->first();

        if($product->available_stock < $order['quantity'])
        {
            return response()->json([
                'message' => 'Failed to order this product due to unavailability of the stock',
            ], 400);
        }

        event(new OrderPlaced($product, $order['quantity']));
        return response()->json([
            'message' => 'You have successfully ordered this product',
        ], 201);
    }
}
