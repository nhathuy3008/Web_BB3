<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;

    protected $keyType = 'string'; // Đặt kiểu khóa là chuỗi
    public $incrementing = false; // Không sử dụng tự động tăng

    protected $fillable = [
        'fullName',
        'email',
        'password',
        'image',
        'enabled',
        'verificationToken',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            $account->id = (string) Str::uuid(); // Tạo UUID cho id
            $account->verificationToken = Str::random(60); // Tạo token xác thực
        });
    }
}