<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\PendingProject;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function createProject(Request $request)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            // $id = auth('api')->user()->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Validate input fields with rules for required fields and maximum lengths
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'goal_amount' => 'required',
                // 'collected_amount' => 'required',
                // 'status' => 'required',
                'start_date' => 'nullable|date',
                'end_date' => 'required|date|after:start_date',
                'cate_id' => 'required',
                'images.*' => 'required|image|mimes:jpg,png,jpeg,gif', // Validate file ảnh

            ], [
                // Custom error messages for missing required fields
                'name.required' => 'Please fill in the name',
                'description.required' => 'Please fill in the description',
                'goal_amount.required' => 'Please fill in the goal amount',
                // 'collected_amount.required' => 'Please fill in the collected amount',
                // 'status.required' => 'Please fill in the status',
                // 'start_date.required' => 'Please fill in the start date',
                'end_date.required' => 'Please fill in the end date',
                'end_date.after:start_date' => 'Please choose the end date after the start date',
                'cate_id.required' => 'Please fill in the category id',
            ]);

            // Get all the request data
            $projectData = $request->except('image');
            $projectData['create_by'] = $currentUser->id;
            $projectData['start_date'] = Carbon::now();
            $projectData['collected_amount'] = 0;
            $projectData['status'] = "Active";

            // $projectData['date'] = Carbon::now(); // Add the current date to 'date'
            if ($projectData['start_date'] > $projectData['end_date']) {
                return response()->json([
                    'error' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
                ], 422);
            }
            // Create a new project using the validated data
            $project = Project::create($projectData);
            // Return a successful response with the created project data
            // Xử lý ảnh và lưu vào bảng images
            if ($request->has('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;

                $path = 'uploads/image/';
                $file->move($path, $filename);
                Image::create([
                    'link' => $path . $filename,
                    'project_id' => $project->id,
                ]);

            }
            return response()->json([
                'message' => 'Project created successfully',
                'data' => $project,
            ], 201);

        } catch (\Exception $e) {
            // Catch any exceptions and return an error response
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getEditProject($id)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            // $id = auth('api')->user()->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $request = Project::where("projects.id", $id)
                ->join('users', 'projects.create_by', '=', 'users.id')
                ->join('images','projects.id', '=', 'images.project_id')
                ->select('projects.*', 'users.username as Creator','images.link as LinkImage')
                ->first();

            if ($request) {
                return response()->json($request);
            } else {
                // If not found, return a 404 error with a message
                return response()->json(['error' => 'Not found detail of project'], 404);
            }
        } catch (\Exception $e) {
            // Return a 500 error with the exception message in case of failure
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function updateProject(Request $request, $id)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            // $id = auth('api')->user()->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Validate the 'status' field from the request
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'goal_amount' => 'required',
                // 'collected_amount' => 'required',
                // 'status' => 'required',
                'start_date' => 'nullable|date',
                'end_date' => 'required|date|after:start_date',
                'cate_id' => 'required',

            ], [
                // Custom error messages for missing required fields
                'name.required' => 'Please fill in the name',
                'description.required' => 'Please fill in the description',
                'goal_amount.required' => 'Please fill in the goal amount',
                // 'collected_amount.required' => 'Please fill in the collected amount',
                // 'status.required' => 'Please fill in the status',
                // 'start_date.required' => 'Please fill in the start date',
                'end_date.required' => 'Please fill in the end date',
                'end_date.after:start_date' => 'Please choose the end date after the start date',
                'cate_id.required' => 'Please fill in the category id',
            ]);
            $project = Project::find($id);
            // $projectData = $request->except('image');
            if ($project) {
                if ($request->input('end_date') < $project['start_date']) {
                    return response()->json([
                        'error' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
                    ], 422);
                }
                $project->update([
                    'name' => $request['name'],
                    'description' => $request('description'),
                    'goal_amount' => $request('goal_amount'),
                    // 'status' => $request->input('status'),
                    'end_date' => $request('end_date'),
                    'cate_id' => $request('cate_id'),
                ]);
                return response()->json([
                    "data" => $project,
                    "message" => "Update status successfully",
                ]);
            } else {
                // If not found, return a 404 error with a custom message
                return response()->json(['message' => 'Project not found'], 404);
            }
        } catch (\Exception $e) {
            // Catch any exceptions and return a 500 error response with the exception message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

    }
    public function deleteProject(Request $request, $id)
    {
        try {
            $currentUser = auth('api')->user();
            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $project = Project::find($id);

            // Check if the project exists
            if ($project) {
                // Perform a soft delete (mark the record as deleted without actually removing it from the database)
                $project->delete();

                // Return a success response after the soft delete
                return response()->json(['message' => 'Project has been soft deleted successfully']);

            } else {
                // If the project is not found, return a 404 error with a custom message
                return response()->json(['error' => 'Project not found'], 404);
            }

        } catch (\Exception $e) {
            // Catch any exceptions and return a 500 error response with the exception message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function restoreProject($id)
    {
        try {
            $currentUser = auth('api')->user();
            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Find the project by its ID, including soft-deleted records
            $project = Project::withTrashed()->find($id);

            // Check if the project exists (either deleted or active)
            if ($project) {
                // Restore the soft-deleted record
                $project->restore();

                // Return a success response after the restoration
                return response()->json(['message' => 'Project has been restored successfully']);

            } else {
                // If the Project is not found, return a 404 error with a custom message
                return response()->json(['error' => 'Project not found'], 404);
            }

        } catch (\Exception $e) {
            // Catch any exceptions and return a 500 error response with the exception message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

    }

    public function getListPendingProject()
    {
        // Lấy thông tin user hiện tại từ token
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $pendingProjects = PendingProject::with('creator')->get();

        return response()->json($pendingProjects, 200);
    }

    // Duyệt dự án
    public function approve($id)
    {
        // Lấy thông tin user hiện tại từ token
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $pendingProject = Project::findOrFail($id);
        $status = "Active";
        if($pendingProject){
            // Update trạng thái dự án
            $pendingProject->status = $status;
            $pendingProject->save();
        }
        return response()->json(['message' => 'Dự án đã được phê duyệt.'], 200);
    }

    // Từ chối dự án
    public function reject($id)
    {
        // Lấy thông tin user hiện tại từ token
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $pendingProject = Project::findOrFail($id);

        $pendingProject->delete();

        return response()->json(['message' => 'Dự án đã bị từ chối.'], 200);
    }

    //tạo người dùng
    public function createUser(Request $request)
    {
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role_id'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = new User;
        $user->username = request()->username;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->role_id = request()->role_id;
        $user->save();

        return response()->json($user, 201);
    }

    //xem danh sách người dùng (Ngoại trừ admin)
    public function getListUser(){
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $users = User::where("users.id","!=","1")
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*',
                'roles.name as Role')
            ->get(); // neu du lieu it thi dung nhu nay

        return response()->json($users);
    }
    //xem thông tin người dùng theo id 
    public function getUserEdit($id){
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::find($id);
        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
    //update thông tin người dùng 
    public function updateUser(Request $request,$id){
        $validated = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' .$id,
            'phone_number' => 'required',
            'address' => 'required',
        ],
            [
                //bao loi khi khong nhap
                'username.required' => 'Pleaser enter username',
                'email.required' => 'Please enter email',
                'email.email' => 'Invalid email',
                'email.unique'=>'Email already exists',
                'phone_number.required' => 'Please enter phone number',
                'address' => 'Please enter address',
            ]);

        User::find($id)->update([
            'username' => $request["username"],
            'email' => $request["email"],
            'phone_number' => $request["phone_number"],
            'address' => $request["address"],
        ]);
        return response()->json(['message' => 'User updated successfully'], 200);
    }
    //xóa người dùng

    public function deleteUser($id){
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User has been deleted'], 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function restoreUser($id){
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::withTrashed()->where('id', $id)->first();
        if ($user) {
            $user->restore();
            return response()->json(['message' => 'User has been restored'], 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}