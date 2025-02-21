<?php

namespace App\Service;

use App\Model\CartItem;

class CartService
{
    public function getCartItems($accountId)
    {
        return CartItem::where('account_id', $accountId)->with('product')->get();
    }

    public function addToCart($accountId, $productId, $quantity)
    {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('account_id', $accountId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Nếu đã có, cập nhật số lượng
            $cartItem->quantity += $quantity;
            $cartItem->calculateTotalPrice(); // Tính lại tổng giá tiền
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới
            $cartItem = CartItem::create([
                'product_id' => $productId,
                'account_id' => $accountId,
                'quantity' => $quantity,
            ]);
            $cartItem->calculateTotalPrice(); // Tính tổng giá tiền
        }

        return $cartItem;
    }

    public function removeFromCart($accountId, $cartItemId)
    {
        $cartItem = CartItem::where('id', $cartItemId)->where('account_id', $accountId)->first();
        
        if ($cartItem) {
            $cartItem->delete();
            return true; // Xóa thành công
        }

        return false; // Không tìm thấy sản phẩm để xóa
    }
}