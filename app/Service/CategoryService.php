<?php
namespace App\Service;

use App\Model\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function saveCategory($data)
    {
        return Category::create($data);
    }

    public function deleteCategory($id)
    {
        Category::destroy($id);
    }
}