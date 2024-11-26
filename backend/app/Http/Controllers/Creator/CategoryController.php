<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    //
    public function getCategories()
    {
        // Truy vấn danh sách tất cả category
        $categories = Category::all(['id', 'name']); // Chỉ trả về id và name

        return response()->json([
            'categories' => $categories,
        ]);
    }
}