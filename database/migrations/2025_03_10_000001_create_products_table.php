<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description'); // 2000 từ sẽ được lưu dưới dạng text
            $table->string('image')->nullable(); // Đường dẫn tới hình ảnh
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết với categories
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}