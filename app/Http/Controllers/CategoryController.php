<?php

namespace App\Http\Controllers;

use App\Model\Category; // Đảm bảo sử dụng 'Models' thay vì 'Model'
use App\Service\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // Lấy danh sách tất cả danh mục
    public function index()
    {
        return $this->categoryService->getAllCategories();
    }

    // Hiển thị thông tin danh mục theo ID
    public function show($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return $category;
    }

    // Hiển thị form tạo mới danh mục
    public function create()
    {
        return response()->json(['message' => 'Truy cập endpoint để tạo mới danh mục']);
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate(Category::rules());

        $category = $this->categoryService->saveCategory($request->all());
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    // Cập nhật thông tin danh mục theo ID
    public function update(Request $request, $id)
    {
        $request->validate(Category::rules($id));

        $category = $this->categoryService->getCategoryById($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->update($request->all());
        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    // Xóa danh mục theo ID
    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}