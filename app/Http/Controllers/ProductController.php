<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Service\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->productService->getAllProducts();
    }

    public function show($id)
    {
        return $this->productService->getProductById($id);
    }

    public function store(Request $request)
    {
        return $this->productService->createProduct($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->productService->updateProduct($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->productService->deleteProduct($id);
    }
}