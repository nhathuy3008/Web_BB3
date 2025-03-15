<?php

namespace App\Model;

use App\Model\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'total_price',
        'status', // pending, completed, canceled
        'payment_method',
    ];

    // Mối quan hệ với Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Mối quan hệ với OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
