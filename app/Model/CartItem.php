<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'total_price',
        'account_id',
        'product_id',
    ];

    // Mối quan hệ với Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Mối quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Tính tổng giá tiền
    public function calculateTotalPrice()
    {
        // Giả sử bạn có một phương thức để lấy giá của sản phẩm
        $productPrice = $this->product->price; // Thay đổi cho phù hợp nếu cần
        $this->total_price = $this->quantity * $productPrice;
        $this->save(); // Lưu lại tổng giá tiền
    }
}