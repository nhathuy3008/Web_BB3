<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của người dùng
    public function index()
    {
        $orders = Order::where('account_id', Auth::id())->with('orderItems.product')->get();
        return response()->json($orders);
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::where('account_id', Auth::id())->where('id', $id)->with('orderItems.product')->first();
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }
        return response()->json($order);
    }

    // Cập nhật trạng thái đơn hàng (Admin hoặc User huỷ đơn)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Cập nhật trạng thái thành công', 'order' => $order]);
    }
}

?>
