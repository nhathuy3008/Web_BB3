<?php

namespace App\Service;

use App\Model\Product; // Sửa namespace cho đúng
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]); // Khởi tạo Cloudinary
    }

    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct($data)
    {
        $product = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->description = $data['description'];
        
        // Xử lý hình ảnh
        if (!empty($data['image'])) {
            $product->image = $this->uploadImage($data['image']);
        }

        $product->category_id = $data['category_id'];
        $product->save();

        return $product;
    }

    public function updateProduct($id, $data)
    {
        $product = $this->getProductById($id);
        $product->name = $data['name'] ?? $product->name;
        $product->price = $data['price'] ?? $product->price;
        $product->description = $data['description'] ?? $product->description;

        // Xử lý hình ảnh
        if (!empty($data['image'])) {
            $product->image = $this->uploadImage($data['image']);
        }

        $product->category_id = $data['category_id'] ?? $product->category_id;
        $product->save();

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->getProductById($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    private function uploadImage($imageData)
{
    // Kiểm tra nếu hình ảnh là base64
    if (strpos($imageData, 'data:image') === 0) {
        $base64Image = explode(',', $imageData)[1];
        $image = base64_decode($base64Image);

        // Tạo tệp tạm thời
        $tempFile = tmpfile();
        fwrite($tempFile, $image);
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        try {
            // Tải lên Cloudinary
            $uploadedImage = $this->cloudinary->uploadApi()->upload($tempFilePath);
            fclose($tempFile);

            return $uploadedImage['secure_url']; // Trả về URL hình ảnh
        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            fclose($tempFile);
            return null; // Trả về null nếu có lỗi
        }
    }
    
    // Nếu hình ảnh là URL, bạn có thể tải lên theo cách khác
    if (filter_var($imageData, FILTER_VALIDATE_URL)) {
        try {
            // Tải lên từ URL
            $uploadedImage = $this->cloudinary->uploadApi()->upload($imageData);
            return $uploadedImage['secure_url']; // Trả về URL hình ảnh
        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            return null; // Trả về null nếu có lỗi
        }
    }

    return null; // Nếu không có hình ảnh
}
}