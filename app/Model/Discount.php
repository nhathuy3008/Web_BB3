<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'amount',
        'discount_percentage', // Tỷ lệ phần trăm giảm giá
        'quantity', // Số lượng mã giảm giá
        'start_date',
        'end_date',
        'product_id',
        'is_global', // Trường mới để xác định loại voucher
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}