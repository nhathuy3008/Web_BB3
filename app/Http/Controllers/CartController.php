<?php

namespace App\Http\Controllers;

use App\Service\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCartItems(Request $request)
    {
        $accountId = $request->input('account_id'); // Lấy accountId từ yêu cầu
        $cartItems = $this->cartService->getCartItems($accountId);
        return response()->json($cartItems);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id', // Kiểm tra valid account_id
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = $this->cartService->addToCart($request->account_id, $request->product_id, $request->quantity);
        return response()->json($cartItem, 201);
    }

    public function removeFromCart(Request $request, $id)
    {
        $accountId = $request->input('account_id'); // Lấy accountId từ yêu cầu
        $result = $this->cartService->removeFromCart($accountId, $id);
        
        if ($result) {
            return response()->json(['message' => 'Item removed successfully'], 200);
        }

        return response()->json(['message' => 'Item not found'], 404);
    }
}