<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Project;
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
    public function getListProjectByCateId($id){
        try {
            // Lấy danh sách tên dự án theo cate_id
            $projects = Project::withCount('payments') 
                ->where('cate_id', $id)
                ->where('status', 'Active') // Điều kiện lấy dự án Active
                ->join('users', 'projects.create_by', '=', 'users.id')
                ->join('categories', 'projects.cate_id', '=', 'categories.id')
                ->join('images', 'projects.id', '=', 'images.project_id')
                ->select('projects.*',
                    'users.username as Creator',
                    'images.link as LinkImage',
                    'users.address as Address',
                    'users.email as Email',
                    'users.phone_number as PhoneNumber',
                    'categories.name as Category',)// Chỉ lấy trường 'name' của các dự án
                ->get();
            // Kiểm tra kết quả và trả về JSON response
            if ($projects->isNotEmpty()) {
                return response()->json($projects);
            } else {
                // Trả về lỗi 404 nếu không tìm thấy dự án
                return response()->json(['error' => 'No projects found for this category'], 404);
            }
        } catch (\Exception $e) {
            // Trả về lỗi 500 nếu có exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}