<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Tạo trường id với kiểu unsignedBigInteger
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Kết nối với bảng products
            $table->uuid('account_id'); // Sử dụng uuid thay vì foreignId
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade'); // Khóa ngoại
            $table->string('content', 200); // Nội dung comment
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}