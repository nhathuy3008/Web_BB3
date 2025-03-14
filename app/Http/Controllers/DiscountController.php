<?php

namespace App\Http\Controllers;

use App\Service\DiscountService;
use App\Model\Product; // Thêm dòng này
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $result = $this->discountService->createDiscount($data);

        if (isset($result['error'])) {
            return response()->json($result['error'], 422);
        }

        return response()->json($result, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $result = $this->discountService->updateDiscount($id, $data);

        if (isset($result['error'])) {
            return response()->json($result['error'], 404);
        }

        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = $this->discountService->deleteDiscount($id);

        if (isset($result['error'])) {
            return response()->json($result['error'], 404);
        }

        return response()->json($result);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $price = $product->price;
        $result = $this->discountService->applyDiscount($request->code, $product->id, $price);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400); // Trả về lỗi với mã trạng thái 400
        }

        return response()->json(['discounted_price' => $result['discounted_price']]);
    }
}