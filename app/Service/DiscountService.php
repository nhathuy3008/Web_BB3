<?php

namespace App\Service;

use App\Model\Discount;
use App\Model\Product; // Thêm dòng này
use Illuminate\Support\Facades\Validator;

class DiscountService
{
    public function createDiscount(array $data)
    {
        $validator = Validator::make($data, [
            'code' => 'required|string|unique:discounts,code',
            'amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_global' => 'required|boolean',
            'product_id' => 'nullable|exists:products,id',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        return Discount::create($data);
    }

    public function updateDiscount($id, array $data)
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return ['error' => 'Discount not found'];
        }

        $validator = Validator::make($data, [
            'code' => 'required|string|unique:discounts,code,' . $id,
            'amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_global' => 'required|boolean',
            'product_id' => 'nullable|exists:products,id',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        $discount->update($data);

        return $discount;
    }

    public function deleteDiscount($id)
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return ['error' => 'Discount not found'];
        }

        $discount->delete();

        return ['success' => 'Discount deleted successfully'];
    }

    public function applyDiscount($inputCode, $productId, $price)
    {
        $discount = Discount::where('code', $inputCode)->first();

        if ($discount) {
            if ($discount->quantity <= 0) {
                return ['error' => 'Voucher has been used up.'];
            }
            if ($discount->start_date > now() || $discount->end_date < now()) {
                return ['error' => 'Voucher is not valid at this time.'];
            }
            if ($discount->is_global || $discount->product_id == $productId) {
                $reducedPrice = $price - ($price * ($discount->discount_percentage / 100));
                $discount->decrement('quantity');
                return ['discounted_price' => $reducedPrice]; // Trả về giá đã giảm
            } else {
                return ['error' => 'This voucher does not apply to this product.'];
            }
        }

        return ['error' => 'Voucher not found.']; // Nếu không tìm thấy voucher
    }
}