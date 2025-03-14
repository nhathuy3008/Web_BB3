<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 8, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_global')->default(false); // Trường mới để xác định loại voucher
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade'); // Khóa ngoại, nullable
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}