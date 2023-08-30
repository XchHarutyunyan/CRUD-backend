<?php

namespace App\Http\Controllers;

use App\DTO\CartDTO;
use App\Http\Requests\StoreCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function store(StoreCartRequest $request)
    {
        $cartDTO = new CartDTO($request->validated());

        $product = Product::find($cartDTO->product_id);

        if (!$product->available) {
            return response()->json(['error' => 'The product is out of stock'], 422);
        }

        $existingCartItem = Cart::where('product_id', $cartDTO->product_id)->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $cartDTO->quantity,
            ]);

            return response()->json(new CartResource($existingCartItem), 200);
        }

        $cart = Cart::create([
            'product_id' => $cartDTO->product_id,
            'quantity' => $cartDTO->quantity,
        ]);

        return response()->json(new CartResource($cart), 201);
    }

    public function destroy($cartId)
    {
        try {
            $cartItem = Cart::findOrFail($cartId);
            $cartItem->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }
    }
}
