<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Tạo cột ID tự động tăng
            $table->string('name')->unique(); // Tạo cột name với ràng buộc duy nhất
            $table->timestamps(); // Tạo cột created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories'); // Xóa bảng nếu cần
    }
}