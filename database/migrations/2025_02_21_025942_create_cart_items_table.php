<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id(); // Tạo trường id với kiểu unsignedBigInteger
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Kết nối với bảng products
            $table->uuid('account_id'); // Sử dụng uuid thay vì foreignId
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade'); // Khóa ngoại
            $table->unsignedInteger('quantity'); // Trường số lượng
            $table->decimal('total_price', 10, 2)->default(0); // Trường tổng giá tiền
            $table->timestamps(); // Các trường created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}