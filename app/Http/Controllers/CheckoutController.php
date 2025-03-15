<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\OrderItem;
use App\Model\Product;
use App\Model\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Xử lý đặt hàng với thanh toán giả lập
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            $totalPrice = 0;
            $items = $request->items;

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    return response()->json(['message' => 'Sản phẩm không đủ hàng'], 400);
                }
                $totalPrice += $product->price * $item['quantity'];
            }

            // Tạo đơn hàng
            $order = Order::create([
                'account_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
                $product->decrement('stock', $item['quantity']);
            }

            // === GIẢ LẬP THANH TOÁN ===
            $paymentResponse = [
                'status' => 'success', // Đổi thành 'failed' để kiểm tra lỗi thanh toán
                'transaction_id' => 'FAKE_TXN_' . uniqid(),
                'message' => 'Thanh toán thành công (mô phỏng)',
            ];

            // Xử lý kết quả thanh toán giả
            if ($paymentResponse['status'] === 'success') {
                $order->update(['status' => 'completed']);
                return response()->json([
                    'message' => 'Đặt hàng thành công, thanh toán giả lập hoàn tất',
                    'order' => $order,
                    'payment' => $paymentResponse,
                ]);
            } else {
                $order->update(['status' => 'canceled']);
                return response()->json(['message' => 'Thanh toán thất bại', 'order' => $order]);
            }
        });
    }

    // Xử lý callback sau khi thanh toán
    public function paymentCallback(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        if ($request->status === 'success') {
            $order->update(['status' => 'completed']);
            return response()->json(['message' => 'Thanh toán thành công', 'order' => $order]);
        }

        $order->update(['status' => 'canceled']);
        return response()->json(['message' => 'Thanh toán thất bại', 'order' => $order]);
    }
}
?>
